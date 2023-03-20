<?php

use UnicaenMail\Controller\MailController;
use UnicaenPrivilege\Guard\PrivilegeController;

return [
    'unicaen-mail' => [

        /**
         * Classe de l'entité
         **/
        'mail_entity_class' => \UnicaenMail\Entity\Db\Mail::class,

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'transport_options' => [
            'host' => AppConfig::get('mail', 'smtpHost'),
            'port' => AppConfig::get('mail', 'smtpPort'),
        ],
        /**
         * Adresses des redirection si do_not_send est à true
         */
        'redirect_to' => AppConfig::get('mail', 'redirection'),
        'do_not_send' => AppConfig::get('mail', 'envoiDesactive'),

        /**
         * Configuration de l'expéditeur
         */
        'subject_prefix' => 'OSE',
        'from_name' => 'Application',
        'from_email' => AppConfig::get('mail', 'from'),
    ],
/*
    'navigation' => [
        'default' => [
            'home' => [
                'pages' => [
                    'administration' => [
                        'pages' => [
                            'mail' => [
                                'label' => 'Courriers électroniques',
                                'route' => 'mail',
                                'resource' => PrivilegeController::getResourceId(MailController::class, 'index'),
                                'order'    => 9003,
                                'icon' => 'fas fa-angle-right',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],*/
];