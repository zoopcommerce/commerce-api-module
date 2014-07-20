<?php

namespace Zoop\Api\Controller\Listener;

use Zend\Mvc\MvcEvent;
use Zoop\Api\Controller\Listener\CorsHeadersTrait;
use Zoop\ShardModule\Controller\Result;

/**
 *
 * @author  Tim Roediger <superdweebie@gmail.com>
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class OptionsListener
{
    use CorsHeadersTrait;
    
    public function options(MvcEvent $event)
    {
        $result = new Result;
        $event->setResult($result);

        $result->setStatusCode(201);

        $this->setCorsHeaders($event);

        return $result;
    }
}
