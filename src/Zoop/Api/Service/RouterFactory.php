<?php

namespace Zoop\Api\Service;

use Zend\Console\Console;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Mvc\Service\RouterFactory as DefaultRouterFactory;
use Zend\Mvc\Router\RouteStackInterface;
use Zend\Mvc\Router\Http\Part;
use Zend\Mvc\Router\RoutePluginManager;

class RouterFactory extends DefaultRouterFactory implements FactoryInterface
{
    protected $routePluginManager;

    public function createService(ServiceLocatorInterface $serviceLocator, $cName = null, $rName = null)
    {
        $router = parent::createService($serviceLocator, $cName, $rName);

        if ($rName === 'ConsoleRouter' || ($cName === 'router' && Console::isConsole())
        ) {
            return $router;
        }

        $this->setRoutePluginManager($router->getRoutePluginManager());
        $this->getRoutePluginManager()->setServiceLocator($serviceLocator);

        $apiConfig = $serviceLocator->get('config')['zoop']['api'];

        $this->createRoutes($router, $apiConfig);

        return $router;
    }

    protected function createRoutes(RouteStackInterface $router, $apiConfig)
    {
        $routeString = $apiConfig['route'];
        $routeName = $apiConfig['name'];
        $routeConstraints = $apiConfig['constraints'];
        $routeConstraints['endpoint'] = $this->getEndpointRegex($apiConfig['endpoints']);

        $routeConfig = [
            'route' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/'
                ],
            ],
            'route_plugins' => $this->getRoutePluginManager(),
            'child_routes' => [],
        ];

        //apply filter routes
        $filteredRoutes = [];
        if (is_array($apiConfig['filters'])) {
            $filteredRoutes = $this->getFilterdRoutes($routeString, $routeConstraints, $apiConfig['filters']);
            $routeConfig['child_routes'] = $filteredRoutes;
        }

        $routeConfig['child_routes']['default'] = $this->getRouteSegment($routeString, $routeConstraints);

        $route = Part::factory($routeConfig);

        $router->addRoute($routeName, $route, -100);
    }

    /**
     *
     * @param string $route
     * @param array $routeConstraints
     * @param array $filters
     * @return array
     */
    protected function getFilterdRoutes($route, array $routeConstraints, array $filters)
    {
        $routes = [];

        foreach ($filters as $name => $filter) {
            $compiledRoute = $filter['route'] . $route;
            $compiledConstraints = array_merge($routeConstraints, $filter['constraints']);
            $routes[$name] = $this->getRouteSegment($compiledRoute, $compiledConstraints);
        }

        return $routes;
    }

    /**
     * @param string $route
     * @param array $constraints
     * @return array
     */
    protected function getRouteSegment($route, array $constraints = [])
    {
        return [
            'type' => 'segment',
            'options' => [
                'route' => ltrim($route, '/'),
                'constraints' => $constraints,
            ],
            'may_terminate' => true,
        ];
    }

    protected function getEndpointRegex($endpoints)
    {
        //sort endpoints by slash
        $regexEndpoints = [];
        foreach ($endpoints as $endpoint) {
            if (strpos($endpoint, '/') !== false) {
                $regexEndpoints[] = $endpoint;
            }
        }

        //reverse sort
        rsort($regexEndpoints);

        foreach ($endpoints as $endpoint) {
            if (!in_array($endpoint, $regexEndpoints)) {
                $regexEndpoints[] = $endpoint;
            }
        }

        return str_replace('/', '\/', implode('|', $regexEndpoints));
    }

    /**
     * @return RoutePluginManager
     */
    public function getRoutePluginManager()
    {
        return $this->routePluginManager;
    }

    /**
     * @param RoutePluginManager $routePluginManager
     */
    public function setRoutePluginManager(RoutePluginManager $routePluginManager)
    {
        $this->routePluginManager = $routePluginManager;
    }
}
