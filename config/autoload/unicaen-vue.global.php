<?php

return [
    'unicaen-vue' => [
        'host'        => 'http://localhost:5133',
        'hot-loading' => \AppConfig::inDev() ? \AppConfig::get('dev', 'hot-loading') : false,
        'dist-path'   => 'public/dist',
        'dist-url'    => 'dist',
    ],
];