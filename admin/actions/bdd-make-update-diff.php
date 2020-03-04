<?php

$bdd    = $oa->getBdd();
$schema = $bdd->getSchema();
$schema->setLogger($c);

$fichier = $oa->getOseDir() . 'cache/bdd-update-diff.sql';

$c->begin("Construction d'un script de mise à jour de la base de données");
$c->msg("Attention : par rapport à update-bdd, seules les définitions des objets sont concernées. Les requêtes de mise à jour des données ne sont pas générées.");

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
$queries = $schema->diff($ref, $ddlConfig);
$sqlDdl  = $schema->queriesToSql($queries);
file_put_contents($fichier, $sqlDdl);

$c->end("Script différentiel créé et enregistré dans le fichier $fichier");