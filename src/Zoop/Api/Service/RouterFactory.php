<?php

namespace Zoop\Api\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Service\RouterFactory as DefaultRouterFactory;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\Mvc\Router\Http\Segment;

class RouterFactory extends DefaultRouterFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $router = parent::createService($serviceLocator);

        $routePluginManager = $router->getRoutePluginManager();
        $routePluginManager->setServiceLocator($serviceLocator);

        $apiConfig = $serviceLocator->get('config')['zoop']['api'];

        $this->createApiRoute($router, $apiConfig);

        return $router;
    }

    protected function createApiRoute(RouteStackInterface $router, $apiConfig)
    {
        $constraints = [
            'endpoint' => $this->getEndpointRegex($apiConfig['endpoints']),
            'id' => '[a-zA-Z0-9/_-]+'
        ];
        $route = new Segment($apiConfig['route'], $constraints);

        //set rest route to the lowest priority so it can be overridden if needed
        $router->addRoute($apiConfig['name'], $route, -1);
    }
    
    protected function getEndpointRegex($endpoints)
    {
        //sort endpoints by slash
        $regexEndpoints = [];
        foreach($endpoints as $endpoint) {
            if(strpos($endpoint, '/') !== false) {
                $regexEndpoints[] = $endpoint;
            }
        }
        
        //reverse sort
        rsort($regexEndpoints);
        
        foreach($endpoints as $endpoint) {
            if(!in_array($endpoint, $regexEndpoints)) {
                $regexEndpoints[] = $endpoint;
            }
        }
        
        return str_replace('/', '\/', implode('|', $regexEndpoints));
    }
}
