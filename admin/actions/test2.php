<?php


// Initialisation
$bdd = $oa->getBdd();

//$ref = new Ddl();
//$ref->loadFromDir(getcwd() . '/data/ddl-pg');



//$d = $bdd->schema()->create(['name' => 'lololo8']);
//$d = $bdd->schema()->rename('lololo8', 'lo9');
//$d = $bdd->schema()->drop('lo9');

$bdd->drop();

