<?php
/*
$bdd = new \BddAdmin\Bdd(Config::get('bdds', 'deploy-local'));
$oa->setBdd($bdd);*/
/*
$schema = new \BddAdmin\Schema($bdd);

/* Récupération du schéma de référence *
$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl.php');

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


$departements = [];

$r = fopen($oa->getOseDir() . 'data/departement.csv', 'r');
$i = 0;
while ($d = fgetcsv($r, 0, ',', '"')) {
    $i++;
    if ($i > 1) {
        $code = (string)$d[0];
        if (2 == strlen($code)) {
            $code = '0' . $code;
        }
        $departements[] = [
            'CODE'    => $code,
            'LIBELLE' => $d[6],
        ];
        $c->println("update departement set libelle = '$d[6]' where code = '$code';");
    }
}

fclose($r);

//var_dump($departements);