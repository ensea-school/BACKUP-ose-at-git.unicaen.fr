<?php

$bdd    = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
$schema = new \BddAdmin\Schema($bdd);

/* Récupération du schéma de référence */
$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl.php');


/* Construction de la config de DDL pour filtrer */
$ddlConfig = require $oa->getOseDir() . 'data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $ddlConfig[$ddlClass]['includes'][] = $object;
    }
}

/* Mise en place du logging en mode console */
$scl          = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);

$res = $schema->majSequences($ref, false);

echo implode( "\n\n/\n\n", array_keys($res['BddAdmin\Ddl\DdlTable.majSequences']));

