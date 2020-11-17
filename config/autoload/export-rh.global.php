<?php

return [
    'export-rh' => [
        'siham-ws' => [
            'actif'    => \AppConfig::get('siham-ws', 'actif', false),
            'uri'      => \AppConfig::get('siham-ws', 'uri'),
            'login'    => \AppConfig::get('siham-ws', 'login'),
            'password' => \AppConfig::get('siham-ws', 'password'),
        ],
    ],
];