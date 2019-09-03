<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'deploy-local'));
$oa->setBdd($bdd);

/* Insertion des données */
$dataGen = new DataGen($oa);

$table = null;
//$table = 'PARAMETRE';

//$bdd->getTable($table)->delete();

$dataGen->install($table);

if ($table) {
    $r = $oa->getBdd()->getTable($table)->select();
    var_dump(count($r));
}