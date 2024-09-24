<?php

$config = \OseAdmin::instance()->config();

return [
    'doctrine'     => [
        'connection'    => [
            'orm_default' => [
                'params' => [
                    'host'          => $config->get('bdd', 'host'),
                    'port'          => $config->get('bdd', 'port'),
                    'dbname'        => $config->get('bdd', 'dbname'),
                    'user'          => $config->get('bdd', 'username'),
                    'password'      => $config->get('bdd', 'password'),
                    'charset'       => 'AL32UTF8',
                    'connectstring' => $config->get('bdd', 'connectstring'),
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
                'generate_proxies' => $config->get('bdd', 'generateProxies'),
                'proxy_dir'        => 'cache/DoctrineProxy',
            ],
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => $config->get('global', 'affichageErreurs'),
    ],
    'cli_config'   => [
        'scheme' => $config->get('global', 'scheme'),
        'domain' => $config->get('global', 'domain'),
    ],

    'session_config' => [
        // Session cookie will expire in 1 hour.
        'cookie_lifetime' => 60 * 60 * 1,
        // Session data will be stored on server maximum for 30 days.
        'gc_maxlifetime'  => 60 * 60 * 24 * 30,
    ],

    'session_manager' => [
        // Session validators (used for security).
        'validators' => [
            Laminas\Session\Validator\RemoteAddr::class,

            // Erreur rencontrée avec ce validateur lorsqu'on passe en "Version pour ordinateur" sur un téléphone Android :
            // `Fatal error: Uncaught Laminas\Session\Exception\RuntimeException: Session validation failed
            //  in /var/www/app/vendor/Laminas/Laminas-session/src/SessionManager.php on line 162`
            //HttpUserAgent::class,
        ],
    ],
    //
    // Session storage configuration.
    //
    'session_storage' => [
        'type' => Laminas\Session\Storage\SessionArrayStorage::class,
    ],
    'application'     => [
        'etats-sortie' => [
            'host'    => $config->get('etats-sortie', 'host', '127.0.0.1'),
            'tmp-dir' => $config->get('etats-sortie', 'tmp-dir', getcwd() . '/cache/'),
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => $config->get('global','affichageErreurs'), // display 404 reason in template
        'display_exceptions'       => $config->get('global','affichageErreurs'),
        ],
];
