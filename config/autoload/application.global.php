<?php

use Laminas\Log\Logger;

return [
    'cli_config' => [
        'scheme' => AppAdmin::config()['global']['scheme'] ?? 'https',
        'domain' => AppAdmin::config()['global']['domain'] ?? null,
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

    'session_storage' => [
        'type' => Laminas\Session\Storage\SessionArrayStorage::class,
    ],

    'application'     => [
        'etats-sortie' => [
            'host'    => AppAdmin::config()['etats-sortie']['host'] ?? '127.0.0.1',
            'tmp-dir' => AppAdmin::config()['etats-sortie']['tmp-dir'] ?? 'cache/',
        ],
    ],

    'view_manager'    => [
        'display_not_found_reason' => AppAdmin::config()['global']['affichageErreurs'] ?? true, // display 404 reason in template
        'display_exceptions'       => AppAdmin::config()['global']['affichageErreurs'] ?? true,
    ],

    'translator' => [
        'locale'                    => AppAdmin::config()['global']['locale'] ?? 'fr_FR',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => 'language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'logger' => 'Laminas\Log\LoggerServiceFactory',
        ],
    ],
    'log'             => [
        'writers' => [
            'stream' => [
                'name'    => 'stream',
                'options' => [
                    'stream'    => '/tmp/ose.log',
                    'filters'   => [
                        'priority' => [
                            'name'    => 'priority',
                            'options' => [
                                'priority' => Logger::DEBUG,
                            ],
                        ],
                        //                        'suppress' => array(
                        //                            'name' => 'suppress',
                        //                            'options' => array(
                        //                                'suppress' => false
                        //                            )
                        //                        )
                    ],
                    'formatter' => [
                        'name'    => 'simple',
                        'options' => [
                            'dateTimeFormat' => 'd-m-Y H:i:s',
                        ],
                    ],
                ],
            ],
        ],
        //        'processors' => array(
        //            array(
        //                'name' => 'backtrace',
        //            ),
        //        ),
    ],
];
