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
if ('development' == getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development' ){
    $modules[] = 'ZendDeveloperTools';
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
