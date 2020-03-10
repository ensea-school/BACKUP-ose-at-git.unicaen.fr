<?php
/*
$bdd = new \BddAdmin\Bdd(Config::get('bdds', 'deploy-local'));
$oa->setBdd($bdd);*/
/*
$schema = new \BddAdmin\Schema($bdd);

/* Récupération du schéma de référence *
$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl');

/* Construction de la config de DDL pour filtrer *
$ddlConfig = require $oa->getOseDir() . 'data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $ddlConfig[$ddlClass]['includes'][] = $object;
    }
}

$mm = new MigrationManager($oa, $schema);
$mm->initTablesDef($ref, $ddlConfig);

$r = $mm->testUtile('FormuleMigrationVolumeHoraireStructuresTestVersCode');
var_dump($r);
*/


$devLocal    = new \BddAdmin\Bdd(Config::get('bdds', 'dev-local'));
$deployLocal = new \BddAdmin\Bdd(Config::get('bdds', 'deploy-local'));
$test        = new \BddAdmin\Bdd(Config::get('bdds', 'test'));

$devLocal->setLogger($c);
$deployLocal->setLogger($c);

//$deployLocal->drop();
//$deployLocal->create($devLocal);


//$devLocal->cloner($deployLocal);

//$devLocal->getTable('ANNEE')->copy($deployLocal);


$ddl = new BddAdmin\Ddl\Ddl();
$ddl->loadFromFile($oa->getOseDir() . 'data/ddl');
$ddl->saveToDir($oa->getOseDir() . 'cache/ddl');


$ddl2 = new BddAdmin\Ddl\Ddl();
$ddl2->loadFromDir($oa->getOseDir() . 'cache/ddl');

$ddl->saveToFile('cache/ddl');
$ddl2->saveToFile('cache/ddl2.php');