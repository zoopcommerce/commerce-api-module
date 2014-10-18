<?php

namespace Zoop\Api\Controller\Listener;

use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zoop\Api\Controller\Listener\CorsHeadersTrait;
use Zoop\ShardModule\Controller\Listener\PrepareViewModelListener as ShardPrepareViewModelListener;

/**
 * @author  Tim Roediger <superdweebie@gmail.com>
 * @author  Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class PrepareViewModelListener extends ShardPrepareViewModelListener
{
    use CorsHeadersTrait;

    public function __call($name, $args)
    {
        /* @var $event MvcEvent */
        $event = $args[0];

        //apply cors headers
        $this->applyCors($event);

        return $this->prepareViewModel($event, $name);
    }

    protected function applyCors(MvcEvent $event)
    {
        $request = $event->getRequest();
        if ($request->getMethod() !== Request::METHOD_OPTIONS) {
            $this->setHeaders($event);
        }
    }
}
