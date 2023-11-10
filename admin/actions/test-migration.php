<?php

use Unicaen\BddAdmin\Ddl\Ddl;

$prepost = $c->getArg()[2];
$action  = $c->getArg()[3];

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

$mm = new MigrationManager($oa, $ref, $filters);
$mm->migration($prepost, $action);