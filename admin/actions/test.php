<?php

use Unicaen\BddAdmin\Ddl\Ddl;

// Initialisation
$bdd = $oa->getBdd();
$bdd->setLogger($c);

//$ref = new Ddl();
//$ref->loadFromDir($oa->getOseDir() . 'data/ddl-pg');


$d = $bdd->table()->exists("toto");

var_dump($d);