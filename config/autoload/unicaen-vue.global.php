<?php

$hostUrl = $_ENV['DEV_VITE_URL'] ?? null;

if (!$hostUrl) {
    $scheme = $_ENV['HTTP_X_FORWARDED_PROTO'] ?? $_ENV['REQUEST_SCHEME'] ?? 'http';
    $host = $_ENV['APP_HOST'] ?? 'localhost';
    $port = $_ENV['DEV_VITE_PORT'] ?? 5133;

    $hostUrl = $scheme . '://' . $host . ':' . $port;

}

return [
    'unicaen-vue' => [
        'host'        => $hostUrl,
        'hot-loading' => AppAdmin::inDev() ? (AppAdmin::config()['dev']['hot-loading'] ?? false) : false,
        'dist-path'   => 'public/dist',
        'dist-url'    => 'dist',
    ],
];
