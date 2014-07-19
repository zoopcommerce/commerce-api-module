<?php

namespace Zoop\Api\Controller\Listener;

use Zend\Http\Request;
use Zend\Http\Header\Allow;
use Zend\Http\Header\GenericHeader;
use Zend\Mvc\MvcEvent;
use Zoop\ShardModule\Controller\Result;

/**
 *
 * @author  Tim Roediger <superdweebie@gmail.com>
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class OptionsListener
{
    public function options(MvcEvent $event)
    {
        $result = new Result;
        $event->setResult($result);

        $result->setStatusCode(201);

        $this->setHeaders($event);

        return $result;
    }
    
    public function setHeaders(MvcEvent $event)
    {
        $result = $event->getResult();

        $methods = [
            Request::METHOD_OPTIONS,
            Request::METHOD_GET,
            Request::METHOD_POST,
            Request::METHOD_PUT,
            Request::METHOD_PATCH,
        ];
                
        $allow = new Allow;
        $allow->allowMethods($methods);

        $result->addHeader($allow);
        $result->addHeader(
            GenericHeader::fromString(
                'Access-Control-Allow-Origin: *'
            )
        );
        $result->addHeader(GenericHeader::fromString(
            'Access-Control-Allow-Methods: ' .
            implode(', ', $methods)
        ));
        $result->addHeader(GenericHeader::fromString('Access-Control-Allow-Headers: content-type'));
        $result->addHeader(GenericHeader::fromString('Access-Control-Max-Age: 1200'));
        $result->addHeader(GenericHeader::fromString('Access-Control-Allow-Credentials: true'));

        return;
    }
}
