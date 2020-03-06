<?php

// Initialisation
$schema = $oa->getBdd()->getSchema();
$schema->setLogger($c);

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);
$c->println("\n" . 'Mise à jour des définitions de la base de données. Merci de patienter ...', $c::COLOR_LIGHT_PURPLE);


// Récupération du schéma de référence
$ref = new \BddAdmin\Ddl\Ddl();
$ref->loadFromFile($oa->getOseDir() . 'data/ddl.php');


// Construction de la config de DDL pour filtrer
$filters = require $oa->getOseDir() . 'data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $filters[$ddlClass]['includes'][] = $object;
    }
}


// Initialisation et lancement de la pré-migration
$mm = new MigrationManager($oa, $schema);
$mm->initTablesDef($ref, $filters);
$mm->migration('pre');


// Mise à jour de la BDD (structures)
$schema->alter($ref, $filters, true);


// Mise à jour des séquences
$schema->majSequences($ref);


// Mise à jour des données
$dataGen = new DataGen($oa);
$dataGen->update();


// Post-migration
$c->println('');
$mm->migration('post');


// Néttoyage des caches
$oa->run('clear-cache');