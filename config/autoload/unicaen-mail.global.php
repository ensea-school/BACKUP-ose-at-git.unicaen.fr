<?php

use UnicaenMail\Entity\Db\Mail;

$conf = AppAdmin::config();

return [
    'unicaen-app' => [

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'mail' => [
            // transport des mails
            'transport_options' => [
                'host' => $conf['mail']['smtpHost'] ?? 'localhost',
                'port' => $conf['mail']['smtpPort'] ?? null,
            ],
            // adresses à substituer à celles des destinataires originaux ('CURRENT_USER' équivaut à l'utilisateur connecté)
            'redirect_to'       => $conf['mail']['redirection'] ?? null,
            // adresse d'expéditeur par défaut
            'from'              => $conf['mail']['from'] ?? null,
            // désactivation totale de l'envoi de mail par l'application
            'do_not_send'       => $conf['mail']['envoiDesactive'] ?? true,
        ],
    ],

    'unicaen-mail' => [
        /**
         * Classe de entité
         **/
        'mail_entity_class' => Mail::class,

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'transport_options' => [
            'host' => $conf['mail']['smtpHost'] ?? 'localhost',
            'port' => $conf['mail']['smtpPort'] ?? null,
        ],

        'redirect_to' => $conf['mail']['redirection'] ?? null,
        'do_not_send' => $conf['mail']['envoiDesactive'] ?? true,
        'redirect'    => !empty($conf['mail']['redirection'] ?? null),

        'subject_prefix' => 'OSE',
        'from_name'      => 'Application',
        'from_email'     => $conf['mail']['from'] ?? null,

        /**
         * Adresses des redirections si do_not_send est à true
         */

        'module' => [
            'default' => [
                'redirect_to'    => $conf['mail']['redirection'] ?? null,
                'do_not_send'    => $conf['mail']['envoiDesactive'] ?? true,
                'redirect'       => !empty($conf['mail']['redirection'] ?? null),
                'subject_prefix' => 'OSE',
                'from_name'      => 'OSE | Application',
                'from_email'     => $conf['mail']['from'] ?? null,

            ],

        ],
    ],

    'server_url' => ($conf['global']['scheme'] ?? 'http') . '://' . ($conf['global']['domain'] ?? 'localhost'),

    /*'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'configuration' => [
                                'pages' => [
                                    'email' => [

                                        'label'    => 'Courriers électroniques',
                                        'route'    => 'mail',
                                        'resource' => PrivilegeController::getResourceId(MailController::class, 'index'),
                                        'order'    => 9003,
                                        'icon'     => 'fas fa-angle-right',
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],*/
];