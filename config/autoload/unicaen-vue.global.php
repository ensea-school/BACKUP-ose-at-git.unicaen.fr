<?php

$hostUrl = ($_ENV['REQUEST_SCHEME'] ?? 'http').'://'.($_ENV['APP_HOST'] ?? 'localhost').':'.($_ENV['APP_VITE_PORT'] ?? 5133);

return [
    'unicaen-vue' => [
        'host'        => $hostUrl,
        'hot-loading' => AppAdmin::inDev() ? (AppAdmin::config()['dev']['hot-loading'] ?? false) : false,
        'dist-path'   => 'public/dist',
        'dist-url'    => 'dist',
    ],
];