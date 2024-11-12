<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config/container.php';

spl_autoload_register(function (string $class) {
    if (str_starts_with($class, 'tests\\')) {
        $filename = str_replace('\\', '/', $class) . '.php';
        require_once $filename;
    }
});