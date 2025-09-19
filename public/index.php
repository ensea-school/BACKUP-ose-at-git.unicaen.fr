<?php

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

$container = require __DIR__ . '/../config/container.php';

$app = $container->get('Application');
$app->run();