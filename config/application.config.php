<?php

$env = getenv('APPLICATION_ENV') ?: 'production';

$modules = [
    'ZfcBase', 'DoctrineModule', 'DoctrineORMModule', 'ZfcUser', 'ZfcUserDoctrineORM',
    'UnicaenApp', 'UnicaenAuth', 'UnicaenImport',
    'Application'
];

if (!\Zend\Console\Console::isConsole()){
    array_unshift($modules, 'BjyAuthorize'); // ne charge BjyAuthorize QUE si on n'est pas en mode console
}


$moduleListenerOptions = [
    'config_glob_paths'    => [
        'config/autoload/{,*.}{global,local}.php',
    ],
    'module_paths' => [
        './module',
        './vendor',
    ],
];

if ( 'development' == $env ) {
    $modules[] = 'ZendDeveloperTools';
    $modules[] = 'UnicaenCode';
}else{
    $moduleListenerOptions['config_cache_enabled']      = true;
    $moduleListenerOptions['module_map_cache_enabled']  = true;
    $moduleListenerOptions['cache_dir'] = 'data/cache/';
}

return [
    'modules' => $modules,
    'module_listener_options' => $moduleListenerOptions,
];