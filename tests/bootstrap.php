<?php

require dirname(__DIR__) . '/admin/src/start.php';

OseAdmin::instance()->start();

spl_autoload_register(function (string $class) {
    if (str_starts_with($class, 'tests\\')) {
        $filename = getcwd() . '/' . str_replace('\\', '/', $class) . '.php';
        require_once $filename;
    }
});