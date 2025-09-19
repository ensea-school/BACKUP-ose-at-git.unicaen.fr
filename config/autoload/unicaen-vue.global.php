<?php

use Framework\Application\Application;

$hostUrl  = $_ENV['DEV_VITE_URL'] ?? null;

$app = Application::getInstance();

if (!$hostUrl) {
    $scheme = $_ENV['HTTP_X_FORWARDED_PROTO'] ?? $_ENV['REQUEST_SCHEME'] ?? 'http';
    $host = $_ENV['APP_HOST'] ?? 'localhost' ?: 'localhost';
    $port = $_ENV['DEV_VITE_PORT'] ?? 5133 ?: 5133;

    $hostUrl = $scheme . '://' . $host . ':' . $port;

}


return [
    'unicaen-vue' => [
        'host'        => $hostUrl,
        'hot-loading' => $app->inDev() ? ($app->config()['dev']['hot-loading'] ?? false) : false,
        'dist-path'   => 'public/dist',
        'dist-url'    => 'dist',
    ],
];
