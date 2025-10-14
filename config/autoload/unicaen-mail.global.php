<?php

use UnicaenMail\Entity\Db\Mail;
use Unicaen\Framework\Application\Application;

$config = Application::getInstance()->config();

return [
    'unicaen-mail' => [
        /**
         * Classe de l'entité
         **/
        'mail_entity_class' => Mail::class,

        /**
         * Options concernant l'envoi de mail par l'application
         */
        'transport_options' => [
            'host' => $config['mail']['smtpHost'] ?? 'localhost',
            'port' => $config['mail']['smtpPort'] ?? null,
            'tls' => $config['mail']['tls'] ?? true,
        ],

        'redirect_to' => $config['mail']['redirection'] ?? null,
        'do_not_send' => $config['mail']['envoiDesactive'] ?? true,
        'redirect'    => !empty($config['mail']['redirection'] ?? null),

        'subject_prefix' => 'OSE',
        'from_name'      => 'Application',
        'from_email'     => $config['mail']['from'] ?? null,

        /**
         * Adresses des redirections si do_not_send est à true
         */

        'module' => [
            'default' => [
                'redirect_to'    => $config['mail']['redirection'] ?? null,
                'do_not_send'    => $config['mail']['envoiDesactive'] ?? true,
                'redirect'       => !empty($config['mail']['redirection'] ?? null),
                'subject_prefix' => 'OSE',
                'from_name'      => 'OSE | Application',
                'from_email'     => $config['mail']['from'] ?? null,

            ],

        ],
    ],

    'server_url' => ($config['global']['scheme'] ?? 'http') . '://' . ($config['global']['domain'] ?? 'localhost'),
];