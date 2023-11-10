<?php

use Doctrine\ORM\EntityManager;

$osedir = $oa->getOseDir();

$c->println("\nNettoyage des caches et mise à jour des proxies", $c::COLOR_LIGHT_CYAN);
try {
    $c->exec([
        "cd $osedir",
        "rm -Rf cache/*",
    ], false);

    /* Nettoyage des proxies */
    /** @var EntityManager $entityManager */
    $entityManager = $oa->getContainer()->get('doctrine.entitymanager.orm_default');
    $destPath = $entityManager->getConfiguration()->getProxyDir();

    if (!is_dir($destPath)) {
        mkdir($destPath, 0775, true);
    }

    $destPath = realpath($destPath);
    $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();
    $entityManager->getProxyFactory()->generateProxyClasses($metadatas, $destPath);

    $c->exec([
        "cd $osedir",
        "chmod -R 777 cache",
    ], false);

    $c->println('Cache nettoyé, proxies actualisés', $c::COLOR_GREEN);
} catch (\Exception $e) {
    $c->println($e->getMessage());
    $c->println('Un problème est survenu : le cache de OSE n\'a pas été vidé. '
        . 'Merci de supprimer le contenu du répertoire /cache de OSE, pis de lancer la commande ./bin/ose clear-cache pour y remédier', $c::COLOR_RED);
}