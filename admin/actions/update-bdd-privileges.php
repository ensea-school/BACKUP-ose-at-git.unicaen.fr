<?php

$oa->getBdd()->data()->run('privileges');

$args = 'UnicaenCode GeneratePrivileges write=true';
$c->passthru("php " . getcwd() . "/public/index.php " . $args);

// Néttoyage des caches
$oa->run('clear-cache');