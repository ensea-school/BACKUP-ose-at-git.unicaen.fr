<?php

$config = require __dir__ . '/../config.local.php';

if ($config['global']['affichageErreurs']) {
    error_reporting(E_ALL);
}
putenv("NLS_LANGUAGE=FRENCH");


return [
    'doctrine'     => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'host'     => $config['bdd']['host'],
                    'port'     => $config['bdd']['port'],
                    'dbname'   => $config['bdd']['dbname'],
                    'user'     => $config['bdd']['username'],
                    'password' => $config['bdd']['password'],
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
                'generate_proxies' => $config['bdd']['generateProxies'],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => $config['global']['affichageErreurs'],
        'display_exceptions'       => $config['global']['affichageErreurs'],
    ],
    'cli_config'   => [
        'scheme' => $config['global']['scheme'],
        'domain' => $config['global']['domain'],
    ],

];