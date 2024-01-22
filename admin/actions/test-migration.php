<?php

use Unicaen\BddAdmin\Ddl\Ddl;

$prepost = $c->getArg()[2];
$action  = $c->getArg()[3];

// Récupération du schéma de référence
$ref = new Ddl();
$ref->loadFromDir(getcwd() . '/data/ddl');


// Construction de la config de DDL pour filtrer
$filters = require getcwd() . '/data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $filters[$ddlClass]['includes'][] = $object;
    }
}

$mm = new MigrationManager($oa, $ref);
$mm->migration($prepost, $action);