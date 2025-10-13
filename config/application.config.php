<?php

if (!file_exists('config.local.php')) {
    die('Le fichier de configuration config.local.php doit être mis en place et configuré, or il n\'a pas été trouvé.');
}

$__app_config = require 'config.local.php';

$moduleListenerOptions = [
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
];

$__app_config['modules']                 = require __DIR__ . '/modules.config.php';
$__app_config['module_listener_options'] = $moduleListenerOptions;

return $__app_config;