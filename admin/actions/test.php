<?php

$bdd = $oa->getBdd();

$dir = getcwd() . "/cache/bdd-save";
$bdd->save($dir, [], ['FICHIER' => function ($d) {
    $d['TAILLE']  = 0;
    $d['CONTENU'] = null;

    return $d;
}]);

//$bdd->load($dir);