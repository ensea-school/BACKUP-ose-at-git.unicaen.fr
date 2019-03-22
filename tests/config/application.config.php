<?php

$env = getenv('APP_ENV') ?: 'production';

$modules = [
    'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM', 'BjyAuthorize',
    'UnicaenApp',
    'UnicaenAuth',
    'UnicaenLdap',
    'Common',
    'Import',
    'Application'
];

if ($env == 'dev') {
    if ( 'dig-40' == getenv('HTTP_HOST')){
        $modules[] = 'Test';
    }
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
