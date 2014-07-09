<?php

namespace Zoop\Api\Controller;

use Zend\Mvc\Controller\AbstractActionController;

/**
 *
 * @author Josh Stuart <josh.stuart@zoopcommerce.com>
 */
class PingController extends AbstractActionController
{
    public function indexAction()
    {
        $response = $this->getResponse();
        $response->setStatusCode(204);
        $response->setContent('');
        return $response;
    }
}
