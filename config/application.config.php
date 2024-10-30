<?php

$oa = OseAdmin::instance();

return [
    'translator'              => [
        'locale' => 'fr_FR',
    ],
    'modules'                 => require __DIR__ . '/modules.config.php',
    'module_listener_options' => [
        //'use_laminas_loader'       => false,
        'config_glob_paths'        => [
            'config/autoload/{,*.}{global,local' . ($oa->env()->inDev() ? ',dev' : '') . '}.php',
        ],
        'module_paths'             => [
            './module',
            './vendor',
        ],
        'cache_dir'                => 'cache/',
        'config_cache_enabled'     => ($oa->env()->inProd() && !$oa->env()->inConsole()),
        'config_cache_key'         => 'application.config.cache',
        'module_map_cache_enabled' => ($oa->env()->inProd() && !$oa->env()->inConsole()),
        'module_map_cache_key'     => 'application.module.cache',
    ],
];