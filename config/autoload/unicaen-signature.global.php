<?php

$config = OseAdmin::instance()->config();

return [
    'unicaen-signature' => [
        'notifications'          => $config->get('unicaen-signature', 'notifications') ? $config->get('unicaen-signature', 'notifications') : [function(){
        }],
        'notifications_messages' => $config->get('unicaen-signature', 'notifications_messages') ? $config->get('unicaen-signature', 'notifications_messages') : [function(){
        }],
        'current_user'           => $config->get('unicaen-signature', 'current_user') ? $config->get('unicaen-signature', 'current_user') : [function(){
        }],
        'documents_path'         => $config->get('unicaen-signature', 'documents_path') ? $config->get('unicaen-signature', 'documents_path') : '',
        'logger'                 => $config->get('unicaen-signature', 'logger') ? $config->get('unicaen-signature', 'logger') : [],
        'letterfiles'            => $config->get('unicaen-signature', 'letterfiles') ? $config->get('unicaen-signature', 'letterfiles') : [],
        'get_recipients_methods' => $config->get('unicaen-signature', 'get_recipients_methods') ? $config->get('unicaen-signature', 'get_recipients_methods') : [],
        'signature_levels'       => $config->get('unicaen-signature', 'signature_levels') ? $config->get('unicaen-signature', 'signature_levels') : [],

    ],
];