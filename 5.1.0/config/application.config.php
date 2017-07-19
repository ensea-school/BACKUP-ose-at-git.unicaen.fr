<?php

$env = getenv('APPLICATION_ENV') ?: 'production';

$modules = [
    'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM',
    'UnicaenApp', 'UnicaenAuth', 'UnicaenImport', 'UnicaenTbl',
    'Application'
];

if (!\Zend\Console\Console::isConsole()){
    array_unshift($modules, 'BjyAuthorize'); // ne charge BjyAuthorize QUE si on n'est pas en mode console
}

if ( 'development' == $env ) {
    $modules[] = 'ZendDeveloperTools';
    $modules[] = 'UnicaenCode';
}

return [
    'translator' => [
        'locale' => 'fr_FR',
    ],
    'modules' => $modules,
    'module_listener_options' => [
        'config_glob_paths'    => [
            'config/autoload/{,*.}{global,local}.php',
        ],
        'module_paths' => [
            './module',
            './vendor',
        ],
        'cache_dir' => 'data/cache/',
    ],
];