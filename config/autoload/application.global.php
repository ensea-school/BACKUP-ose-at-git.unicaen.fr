<?php

$localConfig = require(__DIR__ . '/application.local.php');

if ($localConfig['global']['affichageErreurs']) {
    error_reporting(E_ALL);
}
putenv("NLS_LANGUAGE=FRENCH");


return [
    'doctrine'     => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'host'     => $localConfig['bdd']['host'],
                    'port'     => $localConfig['bdd']['port'],
                    'dbname'   => $localConfig['bdd']['dbname'],
                    'user'     => $localConfig['bdd']['username'],
                    'password' => $localConfig['bdd']['password'],
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
                'generate_proxies' => $localConfig['bdd']['generateProxies'],
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => $localConfig['global']['affichageErreurs'],
        'display_exceptions'       => $localConfig['global']['affichageErreurs'],
    ],
    'cli_config'   => [
        'scheme' => $localConfig['global']['scheme'],
        'domain' => $localConfig['global']['domain'],
    ],

];