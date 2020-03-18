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

$bdd = $oa->getBdd();

$ds           = $bdd->select('SELECT * FROM DEPARTEMENT');
$departements = [];
foreach ($ds as $d) {
    $departements[$d['CODE']] = $d;
}
var_dump($departements);

$sql = '
SELECT DISTINCT 
  I.NUMERO_INSEE, I.NUMERO_INSEE_EST_PROVISOIRE NUMERO_INSEE_PROVISOIRE, D.CODE, D.LIBELLE_LONG
FROM DOSSIER I
JOIN DEPARTEMENT D ON D.ID = I.DEPT_NAISSANCE_ID
WHERE NUMERO_INSEE IS NOT NULL
';

$r     = $bdd->select($sql);
$count = 0;
foreach ($r as $i) {
    $dep = getDep($i['NUMERO_INSEE']);

    if (is_int($dep) && $dep != 99) {
        $dep = '0' . $dep;
    }

    if ($dep != $i['CODE'] && $dep != 99) {
        $count++;
        $prov = $i['NUMERO_INSEE_PROVISOIRE'] == 1 ? ' P' : '  ';
        $c->println($i['NUMERO_INSEE'] . $prov . '       .' . $i['CODE'] . '. ' . $dep);
    }
}

$c->println($count . ' concernés');


//var_dump($r);


function getDep($thisvalue)
{
    $iDepartement = substr(strtoupper($thisvalue), 5, 2);
    if ($iDepartement == '2A' || $iDepartement == '2B') {
        return $iDepartement; // corse
    }
    if ($iDepartement == '99') {
        return 99; // étranger
    }
    if ($iDepartement == '97' || $iDepartement == '98') {
        $iDepartement = substr(strtoupper($thisvalue), 5, 3);

        return (int)$iDepartement;
    }

    return (int)$iDepartement;
}