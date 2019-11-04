<?php

if (!$oa->bddIsOk($msg)) {
    $c->printDie("Impossible d'accéder à la base de données : $msg!"
        . "\nVeuillez contrôler vos paramètres de configuration s'il vous plaît, avant de refaire une tentative de MAJ de la base de données (./bin/ose update-bdd).");
}

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$fichier = $oa->getOseDir().'cache/bdd-update-diff.sql';

$c->println("\nConstruction d'un script de mise à jour de la base de données ...", $c::COLOR_LIGHT_CYAN);
$c->println("Attention : par rapport à update-bdd, seules les définitions des objets sont concernées. Les requêtes de mise à jour des données ne sont pas générées.");

/* Récupération du schéma de référence */
$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl.php');


/* Construction de la config de DDL pour filtrer */
$ddlConfig = require $oa->getOseDir() . 'data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $ddlConfig[$ddlClass]['includes'][] = $object;
    }
}

/* Mise à jour de la BDD */
$queries = $schema->diff($ref, false, $ddlConfig);
$sqlDdl = $schema->queriesToSql($queries);
file_put_contents($fichier, $sqlDdl);

$c->println("Script différentiel créé et enregistré dans le fichier $fichier");