<?php

return [
    'doctrine'     => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'host'     => AppConfig::get('bdd','host'),
                    'port'     => AppConfig::get('bdd','port'),
                    'dbname'   => AppConfig::get('bdd','dbname'),
                    'user'     => AppConfig::get('bdd','username'),
                    'password' => AppConfig::get('bdd','password'),
                    'charset'  => 'AL32UTF8',
                    'persistent' => true,
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'metadata_cache'   => 'array',
                //                'query_cache'      => 'array',
                'result_cache'     => 'array',
                'hydration_cache'  => 'array',
                'generate_proxies' => AppConfig::get('bdd','generateProxies'),
                'proxy_dir'        => 'data/cache/DoctrineProxy',
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => AppConfig::get('global','affichageErreurs'),
        'display_exceptions'       => AppConfig::get('global','affichageErreurs'),
    ],
    'cli_config'   => [
        'scheme' => AppConfig::get('global','scheme'),
        'domain' => AppConfig::get('global','domain'),
    ],

];