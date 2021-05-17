<?php

use BddAdmin\Ddl\Ddl;

// Initialisation
$bdd = $oa->getBdd();
$bdd->setLogger($c);

$c->println("\nMise à jour du connecteur ACTUL+", $c::COLOR_LIGHT_CYAN);


// Récupération du schéma de référence
$ref = new Ddl();
$ref->loadFromDir($oa->getOseDir() . 'admin/actul/ddl');

// Construction de la config de DDL pour filtrer
$filters = [
    'explicit' => true,
];
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $filters[$ddlClass]['includes'][] = $object;
    }
}

// Mise à jour de la BDD (structures)
$bdd->alter($ref, $filters, true);
