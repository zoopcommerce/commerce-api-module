<?php

return [
    'zoop' => [
        'api' => [
            'name' => 'rest',
            'route' => '[/:endpoint][/:id]',
            'endpoints' => []
        ],
        'shard' => [
            'manifest' => [
                'commerce' => [
                    'extension_configs' => [
                        'extension.accesscontrol' => true,
                        'extension.softdelete' => true,
                        'extension.serializer' => true,
                        'extension.state' => true,
                        'extension.zone' => true
                    ],
                ],
            ],
        ],
    ],
    'router' => [
        'prototypes' => [
            'zoop/commerce/api' => [
                'type' => 'Hostname',
                'options' => [
                    'route' => 'api.zoopcommerce.com'
                ],
            ],
        ],
        'routes' => [
            'ping' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/ping',
                    'defaults' => [
                        'controller' => 'zoop.api.controller.ping',
                        'action' => 'index'
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'invokables' => [
            'zoop.api.controller.ping' => 'Zoop\Api\Controller\PingController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'zoop.api.listener.options' => 'Zoop\Api\Controller\Listener\OptionsListener',
            'zoop.api.listener.cors' => 'Zoop\Api\Controller\Listener\CorsListener',
        ],
        'factories' => [
            'Router' => 'Zoop\Api\Service\RouterFactory',
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => array(
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'error/index' => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
