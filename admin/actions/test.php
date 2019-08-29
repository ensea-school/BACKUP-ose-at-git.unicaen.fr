<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'deploy-local'));
$oa->setBdd($bdd);

/* Insertion des données */
$dataGen = new DataGen($oa);

$table = 'SCENARIO';

$bdd->getTable($table)->delete();

$dataGen->update($table);

$r = $oa->getBdd()->getTable($table)->select();
var_dump(count($r));