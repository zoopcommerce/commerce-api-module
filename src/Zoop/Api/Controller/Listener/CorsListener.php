<?php

namespace Zoop\Api\Controller\Listener;

use Zend\Mvc\MvcEvent;
use Zoop\Api\Controller\Listener\CorsHeadersTrait;

/**
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class CorsListener
{
    use CorsHeadersTrait;
    
    public function __call($name, $args)
    {
        /* @var $event MvcEvent */
        $event = $args[0];
        
        //apply cors headers
        $this->setCorsHeaders($event);
    }
}
