<?php


// Initialisation
$bdd = $oa->getBdd();
$bdd->setLogger($c);

//$ref = new Ddl();
//$ref->loadFromDir($oa->getOseDir() . 'data/ddl-pg');



//$d = $bdd->schema()->create(['name' => 'lololo8']);
//$d = $bdd->schema()->rename('lololo8', 'lo9');
$d = $bdd->schema()->drop('lo9');

var_dump($d);