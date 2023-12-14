<?php

// A supprimer pour après la version 20!!!

// Initialisation
$osedir = getcwd();

// Récupération des dépendances
$c->println("\nMise à jour des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
$c->passthru([
    "export COMPOSER_ALLOW_SUPERUSER=1",
    "cd $osedir",
    "php composer.phar self-update --2.2",
    "php composer.phar install --optimize-autoloader",
]);
