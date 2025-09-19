<?php

return [
    'modules'                 => require __DIR__ . '/modules.config.php',
    'module_listener_options' => [
        'use_laminas_loader'       => false,
        'config_glob_paths'        => [
            realpath(__DIR__) . '/autoload/{{,*.}global,{,*.}local}.php',
        ],
        'module_paths'             => [
            './module',
            './vendor',
        ],
        'cache_dir'                => 'cache/',
        'config_cache_enabled'     => PHP_SAPI !== 'cli', // pas de cache en mode cli
        'config_cache_key'         => 'application.config.cache',
        'module_map_cache_enabled' => true,
        'module_map_cache_key'     => 'application.module.cache',
    ],
];