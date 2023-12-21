<?php

use UnicaenMail\Controller\MailController;
use UnicaenMail\Entity\Db\Mail;
use UnicaenPrivilege\Guard\PrivilegeController;

$config = OseAdmin::instance()->config();

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
            'host' => $config->get('mail', 'smtpHost'),
            'port' => $config->get('mail', 'smtpPort'),
        ],

        'redirect_to' => $config->get('mail', 'redirection'),
        'do_not_send' => $config->get('mail', 'envoiDesactive'),
        'redirect'    => !empty($config->get('mail', 'redirection')),

        'subject_prefix' => 'OSE',
        'from_name'      => 'Application',
        'from_email'     => $config->get('mail', 'from'),

        /**
         * Adresses des redirections si do_not_send est à true
         */

        'module' => [
            'default' => [
                'redirect_to'    => $config->get('mail', 'redirection'),
                'do_not_send'    => $config->get('mail', 'envoiDesactive'),
                'redirect'       => !empty($config->get('mail', 'redirection')),
                'subject_prefix' => 'OSE',
                'from_name'      => 'OSE | Application',
                'from_email'     => $config->get('mail', 'from'),

            ],

        ],
    ],

    'server_url' => $config->get('global', 'scheme') . '://' . $config->get('global', 'domain'),

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