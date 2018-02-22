<?php

if (Application::getConfig('global','affichageErreurs')) {
    error_reporting(E_ALL);
}
putenv("NLS_LANGUAGE=FRENCH");


return [
    'doctrine'     => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'host'     => Application::getConfig('bdd','host'),
                    'port'     => Application::getConfig('bdd','port'),
                    'dbname'   => Application::getConfig('bdd','dbname'),
                    'user'     => Application::getConfig('bdd','username'),
                    'password' => Application::getConfig('bdd','password'),
                    'charset'  => 'AL32UTF8',
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'metadata_cache'   => 'array',
                //                'query_cache'      => 'array',
                'result_cache'     => 'array',
                'hydration_cache'  => 'array',
                'generate_proxies' => Application::getConfig('bdd','generateProxies'),
                'proxy_dir'        => 'data/cache/DoctrineProxy',
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => Application::getConfig('global','affichageErreurs'),
        'display_exceptions'       => Application::getConfig('global','affichageErreurs'),
    ],
    'cli_config'   => [
        'scheme' => Application::getConfig('global','scheme'),
        'domain' => Application::getConfig('global','domain'),
    ],

];