<?php

return [
    'zoop' => [
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
            'rest' => [
                //this route will look to load a controller
                //service called `shard.rest.<endpoint>`
                'options' => [
                    'route' => '[/:endpoint][/:id]',
                    'constraints' => [
                        'endpoint' => '[a-zA-Z][a-zA-Z0-9_-]+',
                        'id' => '[a-zA-Z][a-zA-Z0-9/_-]+',
                    ],
                ],
            ],
        ]
    ],
    'controllers' => [
        'invokables' => [
           'zoop.api.controller.ping' => 'Zoop\Api\Controller\PingController'
        ],
    ],
    'service_manager' => [
        'invokables' => [
        ],
        'factories' => [
        ],
    ],
];
