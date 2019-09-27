<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
$schema = new \BddAdmin\Schema($bdd);


$osedir = $oa->getOseDir();

$c->println("\nNettoyage des caches et mise à jour des proxies", $c::COLOR_LIGHT_CYAN);
try {
    $c->exec([
        "cd $osedir",
        "rm -Rf cache/*",
        "php vendor/bin/doctrine-module orm:generate-proxies",
        "chmod -R 777 cache/DoctrineProxy",
        "chmod -R 777 cache/Doctrine",
    ], false);
    $c->println('Cache nettoyé, proxies actualisés', $c::COLOR_GREEN);
} catch (\Exception $e) {
    $c->println($e->getMessage());
    $c->println('Un problème est survenu : le cache de OSE n\'a pas été vidé. '
        .'Merci de supprimer le contenu du répertoire /cache de OSE, pis de lancer la commande ./bin/ose clear-cache pour y remédier', $c::COLOR_RED);
}