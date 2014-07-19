<?php

return [
    'zoop' => [
        'api' => [
            'name' => 'rest',
            'route' => '[/:endpoint][/:id]',
            'endpoints' => []
        ]
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
                    ]
                ]
            ],
        ]
    ],
    'controllers' => [
        'invokables' => [
            'zoop.api.controller.ping' => 'Zoop\Api\Controller\PingController',
        ],
    ],
    'service_manager' => [
        'invokables' => [
            'zoop.api.listener.options' => 'Zoop\Api\Controller\Listener\OptionsListener',
        ],
        'factories' => [
            'Router' => 'Zoop\Api\Service\RouterFactory',
        ],
    ],
];
