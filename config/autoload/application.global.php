<?php

use Unicaen\Framework\Application\Application;

$config = Application::getInstance()->config();

return [
    'cli_config' => [
        'scheme' => $config['global']['scheme'] ?? 'https',
        'domain' => $config['global']['domain'] ?? null,
    ],

    'session_config' => [
        // Durée de la session en secondes => 1h
        'remember_me_seconds' => 60 * 60 * 1,

        // Durée de vie du cookie => 1h
        'cookie_lifetime' => 60 * 60 * 1,

        // Durée de vie des données de session => 30 jours
        'gc_maxlifetime'  => 60 * 60 * 24 * 30,

        // Répertoire où sont stockées les sessions
        'save_path' => 'var/session',
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
            'host'    => $config['etats-sortie']['host'] ?? '127.0.0.1',
            'port'    => $config['etats-sortie']['port'] ?? 80,
            'command' => $config['etats-sortie']['command'] ?? \Unicaen\OpenDocument\Document::CONV_COMMAND_UNOCONV,
            'tmp-dir' => $config['etats-sortie']['tmp-dir'] ?? 'cache/',
        ],
    ],

    'view_manager'    => [
        'display_not_found_reason' => Application::getInstance()->inDev(), // display 404 reason in template
        'display_exceptions'       => Application::getInstance()->inDev(),
    ],

    'translator' => [
        'locale'                    => $config['global']['locale'] ?? 'fr_FR',
        'translation_file_patterns' => [
            [
                'type'     => 'gettext',
                'base_dir' => 'language',
                'pattern'  => '%s.mo',
            ],
        ],
    ],
];
