<?php

return [
    'unicaen-signature' => [
        'notifications'          => AppAdmin::config()['unicaen-signature']['notifications'] ?? [function () {
            }],
        'notifications_messages' => AppAdmin::config()['unicaen-signature']['notifications_messages'] ?? [function () {
            }],
        'current_user'           => AppAdmin::config()['unicaen-signature']['current_user'] ?? [function () {
            }],
        'documents_path'         => 'data/signature',

        'logger'                 => [
            'enable'          => AppAdmin::config()['unicaen-signature']['log'] ?? true,
            'level'           => \Monolog\Logger::DEBUG,
            'file'            => 'data/log/unicaen-signature.log',
            'stdout'          => false,
            'file_permission' => 0666,
            'customLogger'    => null,
        ],
        'letterfiles'            => AppAdmin::config()['unicaen-signature']['letterfiles'] ?? [],

        'get_recipients_methods' => [
            [
                'key'           => 'by_etablissement',
                'label'         => 'Etablissement',                                                         // Intitulé
                'description'   => 'Signature par l\'établissement',                                        // Description
                'getRecipients' => [],
            ],
            [
                'key'           => 'by_intervenant',
                'label'         => 'Intervenant',                                          // Intitulé
                'description'   => 'Signature par l\'intervenant',                         // Description
                'getRecipients' => [],
            ],
            [
                'key'           => 'by_etablissement_and_intervenant',
                'label'         => 'Personnes par rôle',                                   // Intitulé
                'description'   => 'Signature par l\'etablissement et l\'intervenant',     // Description
                'getRecipients' => [],
            ],
        ],
        'signature_levels'       => AppAdmin::config()['unicaen-signature']['signature_levels'] ?? [],
        'hook_recipients'        => AppAdmin::config()['unicaen-signature']['hook_recipients'] ?? [],
    ],
];