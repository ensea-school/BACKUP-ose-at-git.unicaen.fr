<?php

return [
    'doctrine'     => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'host'       => AppConfig::get('bdd', 'host'),
                    'port'       => AppConfig::get('bdd', 'port'),
                    'dbname'     => AppConfig::get('bdd', 'dbname'),
                    'user'       => AppConfig::get('bdd', 'username'),
                    'password'   => AppConfig::get('bdd', 'password'),
                    'charset'    => 'AL32UTF8',
                    //'persistent' => true,
                ],
            ],
        ],
        'configuration' => [
            'orm_default' => [
                'metadata_cache'   => 'filesystem',
//                'query_cache'      => 'filesystem',
                'result_cache'     => 'filesystem',
                'hydration_cache'  => 'array',
                'generate_proxies' => AppConfig::get('bdd', 'generateProxies'),
                'proxy_dir'        => 'cache/DoctrineProxy',
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => AppConfig::get('global', 'affichageErreurs'),
    ],
    'cli_config'   => [
        'scheme' => AppConfig::get('global', 'scheme'),
        'domain' => AppConfig::get('global', 'domain'),
    ],

    'session_config' => [
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60*60*1,
        // Session data will be stored on server maximum for 30 days.
        'gc_maxlifetime'     => 60*60*24*30,
    ],

    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            Zend\Session\Validator\RemoteAddr::class,

            // Erreur rencontrée avec ce validateur lorsqu'on passe en "Version pour ordinateur" sur un téléphone Android :
            // `Fatal error: Uncaught Zend\Session\Exception\RuntimeException: Session validation failed
            //  in /var/www/app/vendor/zendframework/zend-session/src/SessionManager.php on line 162`
            //HttpUserAgent::class,
        ]
    ],
    //
    // Session storage configuration.
    //
    'session_storage' => [
        'type' => Zend\Session\Storage\SessionArrayStorage::class
    ],
];