<?php

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);
if ($oa->oldVersion >= '8.2') {
    $oa->migration('pre');
}

$c->println("\n".'Mise à jour des définitions de la base de données', $c::COLOR_LIGHT_PURPLE);

/* Récupération du schéma de référence */
$ref = $schema->loadFromFile($oa->getOseDir() . 'bdd/ddl.php');


/* Construction de la config de DDL pour filtrer */
$ddlConfig = require $oa->getOseDir().'/data/ddl_config.php';
$classes = [ // Tous les objets de ces classes seront int&égralement pris en compte dans la MAJ
    \BddAdmin\Ddl\DdlView::class,
    \BddAdmin\Ddl\DdlPackage::class,
    \BddAdmin\Ddl\DdlTrigger::class,
];

foreach ($classes as $ddlClass) {
    if (isset($ref[$ddlClass])){
        $objects = array_keys($ref[$ddlClass]);
        foreach($objects as $object){
            $ddlConfig[$ddlClass]['includes'][] = $object;
        }
    }
}

var_dump($ddlConfig);die();
/* Mise en place du logging en mode console */
$scl          = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);

/* Mise à jour de la BDD */
$schema->alter($ref, $ddlConfig, true);

$c->println('Fin de mise à jour des définitions');
$c->println('');

// On teste que la méthode existe, car au moment de la MAJ l'objet chargé est la version antérieure à celle de ce sccript
if (method_exists($oa,'majPrivileges')) { /** @deprecated > 8.2 */
    $c->println('Mise à jour des données', $c::COLOR_LIGHT_PURPLE);
    $c->println('  * Privilèges ...');
    $oa->majPrivileges();

    $c->println('  * États de sortie ...');
    $esData = require $oa->getOseDir() . 'data/etats_sortie.php';
    $bdd->getTable('ETAT_SORTIE')->merge($esData, 'CODE', ['update' => false, 'delete' => false]);

    $c->println('Fin de la mise à jour des données');
}
$c->println('');
if ($oa->oldVersion >= '8.2') {
    $oa->migration('post');
}
$c->println('');