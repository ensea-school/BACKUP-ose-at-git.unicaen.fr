<?php

$bdd = new \BddAdmin\Bdd( Config::get()['bdds']['deploy-local'] );
$bdd->debug = true;

$es = require $oa->getOseDir() . 'data/etats_sortie.php';

/* Mise à jour */
$bdd->getTable('ETAT_SORTIE')->merge($es, 'CODE', ['update' => false, 'delete' => false]);
