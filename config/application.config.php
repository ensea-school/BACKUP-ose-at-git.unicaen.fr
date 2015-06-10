<?php

$env = getenv('APP_ENV') ?: 'production';

$modules = [
    'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM', 'BjyAuthorize',
    'UnicaenApp', //'AssetManager',
    'UnicaenAuth',
    'UnicaenLdap',
    'Common',
    'Application',
    'Import',
];

if ( file_exists(dirname(dirname(__FILE__)).'/module/Debug') ) {
    $modules[] = 'Debug';
}

return [
    'modules' => $modules,
    'module_listener_options' => [
        'config_glob_paths'    => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './module',
            './vendor',
        ],
    ],
];