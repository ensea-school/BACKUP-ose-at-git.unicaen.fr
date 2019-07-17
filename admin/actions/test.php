<?php

$bdd = new \BddAdmin\Bdd( Config::get()['bdds']['deploy-local'] );
$bdd->debug = true;
$schema = new \BddAdmin\Schema($bdd);

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

var_dump($ddlConfig);