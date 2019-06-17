<?php

$osedir = $oa->getOseDir();

// Néttoyage des caches et mise à jour des proxies
$c->exec([
    "cd $osedir",
    "rm -Rf cache/*",
    "php vendor/bin/doctrine-module orm:generate-proxies",
    "chmod -R 777 cache/DoctrineProxy",
    "chmod -R 777 cache/Doctrine",
]);