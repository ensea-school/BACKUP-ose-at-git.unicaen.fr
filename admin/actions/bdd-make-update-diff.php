<?php

$bdd    = $oa->getBdd();
$schema = $bdd->getSchema();
$schema->setLogger($c);

$fichier = $oa->getOseDir() . 'cache/bdd-update-diff.sql';

$c->begin("Construction d'un script de mise à jour de la base de données");
$c->msg("Attention : par rapport à update-bdd, seules les définitions des objets sont concernées. Les requêtes de mise à jour des données ne sont pas générées.");

/* Récupération du schéma de référence */
$ref = new BddAdmin\Ddl\Ddl;
$ref->loadFromFile($oa->getOseDir() . 'data/ddl.php');


/* Construction de la config de DDL pour filtrer */
$filters = require $oa->getOseDir() . 'data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $filters[$ddlClass]['includes'][] = $object;
    }
}

/* Mise à jour de la BDD */
$diff = $schema->diff($ref, $filters);
$sql  = $diff->toScript();
file_put_contents($fichier, $sql);

$c->end("Script différentiel créé et enregistré dans le fichier $fichier");