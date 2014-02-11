<?php
return array(
    'modules' => array(
        'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM', 'BjyAuthorize',
        'UnicaenApp', 'AssetManager',
        'UnicaenAuth',
        'UnicaenLdap',
//        'Test',
        'Common',
        'Application',
        'Import'
    ),
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

