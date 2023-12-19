<?php

// répertoire courant de l'application, accessible via getcwd() partout
chdir(dirname(dirname(__DIR__)));

/* Chargement de l'autoloader du vendor */
if (file_exists(getcwd() . '/vendor/autoload.php')) {
    require_once getcwd() . '/vendor/autoload.php';
}

// Autochargement de fichiers supplémentaires pour OseAdmin
spl_autoload_register(function ($class) {
    $dirs = [
        getcwd().'/admin/src/' => null,
        getcwd().'/admin/actul/src/' => null,
    ];
    foreach ($dirs as $dir => $ns) {
        if (!$ns || str_starts_with($class, $ns)) {
            $filename = $dir . str_replace('\\', '/', $class) . '.php';

            if (file_exists($filename)) {
                require_once $filename;
                break;
            }
        }
    }
});

return OseAdmin::instance();