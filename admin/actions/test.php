<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'deploy-local'));
$oa->setBdd($bdd);
$bdd->debug = true;
/* Insertion des données */
$dataGen = new DataGen($oa);

$table = null;
$table = 'PRIVILEGE';

//$bdd->getTable($table)->delete();

$dataGen->install($table);

if ($table) {
    $r = $oa->getBdd()->getTable($table)->select();
    var_dump(count($r));
}