<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
$schema = new \BddAdmin\Schema($bdd);

