<?php

return [
    // Additional modules to include when in development mode
    'modules' => [
        'Laminas\DeveloperTools',
        'UnicaenCode'
    ],
    // Configuration overrides during development mode
    'module_listener_options' => [
        'config_glob_paths' => [realpath(__DIR__) . '/autoload/{,*.}dev.php'],
        'config_cache_enabled' => false,
        'module_map_cache_enabled' => false,
    ],
];