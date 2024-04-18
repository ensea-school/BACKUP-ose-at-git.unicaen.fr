<?php

use Unicaen\BddAdmin\Ddl\Ddl;


$prepost = $c->getArg()[2];
$action = $c->getArg()[3];

$bdd = $oa->getBdd();

// Récupération du schéma de référence
$ref = new Ddl();
$ref->loadFromDir($bdd->getOption($bdd::OPTION_DDL_DIR));


// Construction de la config de DDL pour filtrer
$filters = $ref->makeFilters();
$filters->addArray(require getcwd() . '/data/ddl_config.php');

$mm = $bdd->migration();
$mm->init($ref, $filters);
$mm->migration($prepost, $action);