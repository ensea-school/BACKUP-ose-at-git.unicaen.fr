<?php

$env = getenv('APP_ENV') ?: 'production';

$modules = array(
    'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM', 'BjyAuthorize',
    'UnicaenApp', //'AssetManager',
    'UnicaenAuth',
    'UnicaenLdap',
    'Common',
    'Import',
    'Application'
);

if ($env == 'development') {
    if ( 'dig-40' == getenv('HTTP_HOST')){
        $modules[] = 'Test';
    }
}

return array(
    'modules' => $modules,
    'module_listener_options' => array(
        'config_glob_paths'    => array(
            'config/autoload/{,*.}{global,local}.php',
        ),
        'module_paths' => array(
            './module',
            './vendor',
        ),
    ),
);
