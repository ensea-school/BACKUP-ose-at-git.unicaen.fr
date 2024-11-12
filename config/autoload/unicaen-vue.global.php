<?php

return [
    'unicaen-vue' => [
        'host'        => 'http://localhost:5133',
        'hot-loading' => AppAdmin::inDev() ? (AppAdmin::config()['dev']['hot-loading'] ?? false) : false,
        'dist-path'   => 'public/dist',
        'dist-url'    => 'dist',
    ],
];