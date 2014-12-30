<?php
/**
 * @package    Zoop
 */
namespace Zoop\Api\Controller\Listener;

use Zend\Mvc\MvcEvent;
use Zoop\ShardModule\Controller\Result;
use Zoop\ShardModule\Controller\Listener\DeleteListener as ShardDeleteListener;
use Zoop\ShardModule\Options\RestfulControllerOptions;
use Zoop\Shard\SoftDelete\SoftDeleter;
use Zoop\ShardModule\Exception\DocumentNotFoundException;
use Zoop\ShardModule\Exception\AccessControlException;

/**
 * @since   1.0
 * @version $Revision$
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class DeleteListener extends ShardDeleteListener
{
    protected function doAction(MvcEvent $event, $metadata, $documentManager)
    {
        if ($document = $event->getParam('document')) {
            // document already loaded, so just remove it
            $this->softDelete($event, $documentManager, $document);
        } else {
            $document = $this->getDocument($event, $metadata, $documentManager);
            if (!empty($document)) {
                $this->softDelete($event, $documentManager, $document);
            } else {
                throw new DocumentNotFoundException("Cannot find the document to delete");
            }
        }

        $result = new Result([]);
        $result->setStatusCode(204);

        $event->setResult($result);

        return $result;
    }
    
    /**
     * @param MvcEvent $event
     * @return RestfulControllerOptions
     */
    protected function getOptions(MvcEvent $event)
    {
        return $event->getTarget()->getOptions();
    }
    
    /**
     * @param MvcEvent $event
     * @return SoftDeleter
     */
    protected function getSoftDeleter(MvcEvent $event)
    {
        $options = $this->getOptions($event);
        $manifest = $options->getManifest();
        return $manifest->getServiceManager()->get('softDeleter');
    }
    
    /**
     * Soft deletes the document
     * 
     * @param MvcEvent $event
     * @param mixed $documentManager
     * @param mixed $document
     */
    protected function softDelete(MvcEvent $event, $documentManager, $document)
    {
        $softDeleter = $this->getSoftDeleter($event);
        $metadata = $documentManager->getClassMetadata(get_class($document));
        $softDeleter->softDelete($document, $metadata);
        
        if (!$softDeleter->isSoftDeleted($document, $metadata)) {
            throw new AccessControlException("You are not authorized to delete this document");
        }
    }
    
    /**
     * 
     * @param MvcEvent $event
     * @param mixed $metadata
     * @param mixed $documentManager
     * @return mixed
     */
    protected function getDocument(MvcEvent $event, $metadata, $documentManager)
    {
        $options = $this->getOptions($event);
        
        $document = $documentManager
            ->createQueryBuilder()
            ->find($metadata->name)
            ->field($options->getProperty())->equals($event->getParam('id'))
            ->getQuery()
            ->getSingleResult();
        
        return $document;
    }
}
