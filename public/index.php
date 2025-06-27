<?php

declare(strict_types=1);

use Laminas\Mvc\Application;
//use App\Kernel;

/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

ini_set('session.cookie_samesite', 'Strict');

if (!defined('REQUEST_MICROTIME')) {
    define('REQUEST_MICROTIME', microtime(true));
}


// Decline static file requests back to the PHP built-in webserver
if (php_sapi_name() === 'cli-server') {
    $path = realpath(__DIR__ . parse_url($_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH));
    if (is_string($path) && __FILE__ !== $path && is_file($path)) {
        return false;
    }
    unset($path);
}

if (str_starts_with($_SERVER['REQUEST_URI'],'/api')){
//    require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

//    return function (array $context) {
//        return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
//    };
    die('Aucune API n\'existe pour le moment');
}else {
    // Composer autoloading
    include __DIR__ . '/../vendor/autoload.php';


    if (! class_exists(Application::class)) {
        throw new RuntimeException(
            "Unable to load application.\n"
            . "- Type `composer install` if you are developing locally.\n"
            . "- Type `docker-compose run laminas composer install` if you are using Docker.\n"
        );
    }

    $container = require __DIR__ . '/../config/container.php';

    // Run the application!
    /** @var Application $app */
    $app = $container->get('Application');
    $app->run();
}