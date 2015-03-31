<?php

/* Fermeture du service *
if (! in_array($_SERVER['REMOTE_ADDR'],[
    '127.0.0.1',
    '10.14.1.39', // Laurent
])){
    $maintenanceText = 'OSE est en cours de mise à jour. Merci de revenir en fin de matinée.';
    //echo $_SERVER['REMOTE_ADDR'];
    include 'maintenance.php';
}
/* Fin de fermeture du service*/

define('APPLICATION_PATH', realpath(__DIR__ . "/.."));

define('REQUEST_MICROTIME', microtime(true));
/**
 * This makes our life easier when dealing with paths. Everything is relative
 * to the application root now.
 */
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();