<?php

/* Fermeture du service *
if (php_sapi_name() === 'cli') {
    exit(0);
}
$whiteList = [
    ['127.0.0.1'], // localhost
    ['10.14.1.38', '10.60.11.40'], // Laurent
];
$passed = false;
foreach( $whiteList as $ip ){
    $passed = $ip[0] === $_SERVER['REMOTE_ADDR'];
    if ($passed && isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
        $passed = isset($ip[1]) && $ip[1] === $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    if ($passed) break;
}
if (!$passed){
    $maintenanceText = 'Suite à un problème matériel, OSE est inaccessible. La résolution est en cours. Veuillez nous excuser pour la gêne occasionnée.';
    include 'maintenance.php';
}
/* Fin de fermeture du service*/

\Locale::setDefault('fr_FR');
define('REQUEST_MICROTIME', microtime(true));
chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();