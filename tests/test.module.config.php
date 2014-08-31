<?php

return [
    'router' => [
        'prototypes' => [
            'zoop/commerce/api' => [
                'type' => 'Hostname',
                'options' => [
                    'route' => 'api.zoopcommerce.local'
                ],
            ],
        ]
    ],
    'doctrine' => [
        'odm' => [
            'connection' => [
                'commerce' => [
                    'dbname' => 'zoop_test',
                    'server' => 'localhost',
                    'port' => '27017',
                    'user' => '',
                    'password' => '',
                ],
            ],
            'configuration' => [
                'commerce' => [
                    'metadata_cache' => 'doctrine.cache.array',
                    'default_db' => 'zoop_test',
                    'generate_proxies' => true,
                    'proxy_dir' => __DIR__ . '/../data/proxies',
                    'proxy_namespace' => 'proxies',
                    'generate_hydrators' => true,
                    'hydrator_dir' => __DIR__ . '/../data/hydrators',
                    'hydrator_namespace' => 'hydrators',
                ]
            ],
        ],
    ],
    'zoop' => [
        'api' => [
            'endpoints' => [
                'test',
            ],
            'filters' => [
                'name' => [
                    'route' => '/name/:name',
                    'constraints' => [
                        'name' => '[a-zA-Z0-9/_-]+'
                    ]
                ]
            ]
        ],
        'aws' => [
            'key' => 'AKIAJE2QFIBMYF5V5MUQ',
            'secret' => '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',
            's3' => [
                'buckets' => [
                    'test' => 'zoop-web-assets-test',
                ],
                'endpoint' => [
                    'test' => 'https://zoop-web-assets-test.s3.amazonaws.com',
                ],
            ],
        ],
        'db' => [
            'host' => 'localhost',
            'database' => 'zoop_development',
            'username' => 'root',
            'password' => 'reverse',
            'port' => 3306,
        ],
        'cache' => [
            'handler' => 'mongodb',
            'mongodb' => [
                'host' => 'localhost',
                'database' => 'zoop_test',
                'collection' => 'Cache',
                'username' => '',
                'password' => '',
                'port' => 27017,
            ],
        ],
        'sendgrid' => [
            'username' => '',
            'password' => ''
        ],
        'session' => [
            'handler' => 'mongodb',
            'mongodb' => [
                'host' => 'localhost',
                'database' => 'zoop_test',
                'collection' => 'Session',
                'username' => '',
                'password' => '',
                'port' => 27017,
            ]
        ],
        'shard' => [
            'manifest' => [
                'commerce' => [
                    'models' => [
                        'Zoop\Api\Test\Assets' => __DIR__ . '/Zoop/Api/Test/Assets',
                    ],
                ],
            ],
            'rest' => [
                'manifest' => 'commerce',
                'cache_control' => [
                    'no_cache' => true
                ],
                'rest' => [
                    'test' => [
                        'property' => 'id',
                        'class' => 'Zoop\Api\Test\Assets\TestModel',
                        'listeners' => [
                            'create' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.shardmodule.listener.create',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.location',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'delete' => [
                                'zoop.shardmodule.listener.delete',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'deleteList' => [],
                            'get' => [
                                'zoop.shardmodule.listener.get',
                                'zoop.shardmodule.listener.serialize',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'getList' => [
                                'zoop.shardmodule.listener.getlist',
                                'zoop.shardmodule.listener.serialize',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'patch' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.shardmodule.listener.idchange',
                                'zoop.shardmodule.listener.patch',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'patchList' => [],
                            'update' => [
                                'zoop.shardmodule.listener.unserialize',
                                'zoop.shardmodule.listener.idchange',
                                'zoop.shardmodule.listener.update',
                                'zoop.shardmodule.listener.flush',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                            'replaceList' => [],
                            'options' => [
                                'zoop.api.listener.options',
                                'zoop.shardmodule.listener.prepareviewmodel'
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]
];
