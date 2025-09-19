<?php

use Framework\Application\Application;

$config = Application::getInstance()->config();

return [
    'unicaen-signature' => [
        'notifications'          => $config['unicaen-signature']['notifications'] ?? [function () {
            }],
        'notifications_messages' => $config['unicaen-signature']['notifications_messages'] ?? [function () {
            }],
        'current_user'           => $config['unicaen-signature']['current_user'] ?? [function () {
            }],
        'documents_path'         => 'data/signature',

        'logger'                 => [
            'enable'          => $config['unicaen-signature']['log'] ?? true,
            'level'           => \Monolog\Logger::DEBUG,
            'file'            => 'data/log/unicaen-signature.log',
            'stdout'          => false,
            'file_permission' => 0666,
            'customLogger'    => null,
        ],
        'letterfiles'            => $config['unicaen-signature']['letterfiles'] ?? [],

        'get_recipients_methods' => [
            [
                'key'           => 'by_etablissement',
                'label'         => 'Etablissement',
                'description'   => 'Signature par l\'établissement',
                'getRecipients' => [],
            ],
            [
                'key'           => 'by_intervenant',
                'label'         => 'Intervenant',
                'description'   => 'Signature par l\'intervenant',
                'getRecipients' => [],
            ],
            [
                'key'           => 'by_etablissement_and_intervenant',
                'label'         => 'Personnes par rôle',
                'description'   => 'Signature par l\'etablissement et l\'intervenant',
                'getRecipients' => [],
            ],
        ],
        'signature_levels'       => $config['unicaen-signature']['signature_levels'] ?? [],
        'hook_recipients'        => $config['unicaen-signature']['hook_recipients'] ?? [],
    ],
];