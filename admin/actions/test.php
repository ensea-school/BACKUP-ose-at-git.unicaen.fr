<?php

$bdd = $oa->getBdd();

$schema = new \BddAdmin\Schema($bdd);
$ref = $schema->loadFromFile($oa->getOseDir().'bdd/ddl.php');


$ddlConfig = [];
// On me met à jour que les objets présents dans le schéma par défaut
foreach( $ref as $ddlClass => $config ){
    $ddlConfig[$ddlClass] = ['includes' => array_keys($config)];
}

$ddlConfig = [\BddAdmin\Ddl\DdlSequence::class => $ddlConfig[\BddAdmin\Ddl\DdlSequence::class]];

$scl = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);

$r = $schema->alter($ref, $ddlConfig, false);

//var_dump($r);
