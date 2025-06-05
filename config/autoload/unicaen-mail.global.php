<?php

use UnicaenMail\Entity\Db\Mail;

$conf = AppAdmin::config();

return [
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