<?php

use BddAdmin\Ddl\Ddl;

// Initialisation
$bdd = $oa->getBdd();
$bdd->setLogger($c);

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);
$c->println("\n" . 'Mise à jour des définitions de la base de données. Merci de patienter ...', $c::COLOR_LIGHT_PURPLE);


// Récupération du schéma de référence
$ref = new Ddl();
$ref->loadFromDir($oa->getOseDir() . 'data/ddl');


// Construction de la config de DDL pour filtrer
$filters = require $oa->getOseDir() . 'data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $filters[$ddlClass]['includes'][] = $object;
    }
}

$tablesDep = [
    Ddl::INDEX,
    Ddl::PRIMARY_CONSTRAINT,
    Ddl::REF_CONSTRAINT,
    Ddl::UNIQUE_CONSTRAINT,
];

foreach ($tablesDep as $tableDep) {
    $objects = $bdd->manager($tableDep)->get();
    foreach ($objects as $obj) {
        if (in_array($obj['table'], $filters['table']['includes'])) {
            $filters[$tableDep]['includes'][] = $obj['name'];
        }
    }
}


// Initialisation et lancement de la pré-migration
$mm = new MigrationManager($oa, $bdd);
$mm->initTablesDef($ref, $filters);
$mm->migration('pre');


// Mise à jour de la BDD (structures)
$bdd->alter($ref, $filters, true);


// Mise à jour des séquences
$bdd->majSequences($ref);

// Reconstruction des TBL
$c->begin("Reconstruction de tous les tableaux de bord");
$oa->exec('UnicaenTbl build-procedures');
$c->end();


// Mise à jour des données
$dataGen = new DataGen($oa);
$dataGen->update();


// Post-migration
$c->println('');
$mm->migration('post');

// Recalcul des vues matérialisées
$bdd->refreshMaterializedViews();

// Néttoyage des caches
$oa->run('clear-cache');