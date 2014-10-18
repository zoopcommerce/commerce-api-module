<?php

namespace Zoop\Api;

use Zend\Mvc\MvcEvent;
use Zoop\Api\RouteListener;

/**
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class Module
{
    public function onBootstrap(MvcEvent $event)
    {
        $eventManager = $event->getApplication()->getEventManager();
        $eventManager->attachAggregate(new RouteListener);
    }

    /**
     *
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . '/../../../config/module.config.php';
    }
}
