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
    ]
];
