<?php

return [
    'unicaen-signature' => [
        'notifications'          => AppAdmin::config()['unicaen-signature']['notifications'] ?? [function () {
            }],
        'notifications_messages' => AppAdmin::config()['unicaen-signature']['notifications_messages'] ?? [function () {
            }],
        'current_user'           => AppAdmin::config()['unicaen-signature']['current_user'] ?? [function () {
            }],
        'documents_path'         => AppAdmin::config()['unicaen-signature']['documents_path'] ?? '',
        'logger'                 => AppAdmin::config()['unicaen-signature']['logger'] ?? [],
        'letterfiles'            => AppAdmin::config()['unicaen-signature']['letterfiles'] ?? [],
        'get_recipients_methods' => AppAdmin::config()['unicaen-signature']['get_recipients_methods'] ?? [],
        'signature_levels'       => AppAdmin::config()['unicaen-signature']['signature_levels'] ?? [],
        'hook_recipients'        => AppAdmin::config()['unicaen-signature']['hook_recipients'] ?? [],

    ],
];