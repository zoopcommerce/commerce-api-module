<?php

namespace Zoop\Api\Controller\Listener;

use Zend\Http\Request;
use Zend\Http\Header\Allow;
use Zend\Http\Header\GenericHeader;
use Zend\Mvc\MvcEvent;
use Zend\Http\Header\Origin;
use Zoop\ShardModule\Controller\Result;

/**
 * @author  Tim Roediger <superdweebie@gmail.com>
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
trait CorsHeadersTrait
{
    /**
     * Sets the cross origin domain headers
     * 
     * @param MvcEvent $event
     * @return
     */
    public function setCorsHeaders(MvcEvent $event)
    {
        $result = $event->getResult();
        $origin = $event->getRequest()->getHeaders()->get('Origin');
        
        if ($result instanceof Result && $origin instanceof Origin) {
            $methods = [
                Request::METHOD_OPTIONS,
                Request::METHOD_GET,
                Request::METHOD_POST,
                Request::METHOD_PUT,
                Request::METHOD_PATCH,
                Request::METHOD_DELETE,
            ];
            
            $allowedHeaders = [
                'authorization',
                'content-type',
                'cache-control',
                'range',
                'x-file-name',
                'x-file-size',
                'x-requested-with',
            ];

            $allow = new Allow;
            $allow->allowMethods($methods);
            $allow->disallowMethods([]);

            $result->addHeader($allow);
            $result->addHeader(
                GenericHeader::fromString(
                    'Access-Control-Allow-Origin: ' . $origin->getFieldValue()
                )
            );
            $result->addHeader(GenericHeader::fromString(
                'Access-Control-Allow-Methods: ' .
                implode(', ', $methods)
            ));
            $result->addHeader(
                GenericHeader::fromString(
                    'Access-Control-Allow-Headers: ' .
                    implode(', ', $allowedHeaders)
                )
            );
            $result->addHeader(GenericHeader::fromString('Access-Control-Max-Age: 1200'));
            $result->addHeader(GenericHeader::fromString('Access-Control-Allow-Credentials: true'));
        }
        return;
    }
}
