<?php

$dirname = $oa->getOseDir() . 'admin/actul/ddl';

$tables = [
    'ACT_DIPLOME',
    'ACT_ELEMENT_COMMUN',
    'ACT_ELEMENT_EFFECTIFS',
    'ACT_ETAPE',
    'ACT_ODF_RELATIONS',
    'ACT_RESP_DIPLOME',
    'ACT_RESP_ETP',
    'ACT_RESP_VDI',
    'ACT_RESP_VET',
    'ACT_VDI_VET',
    'ACT_VET_EFFECTIFS',
    'ACT_VOLUME_HORAIRE_ENS',
];

$filters = [
    'explicit'           => true,
    'table'              => ['includes' => $tables],
    'primary-constraint' => ['includes' => '%'],
];


$c->begin('Génération de la DDL du connecteur ACTUL+ à partir de la base de données');
$ddl = $oa->getBdd()->getDdl($filters);
$pc  = $ddl->get('primary-constraint');
foreach ($pc as $n => $d) {
    if (!in_array($d['table'], $tables)) {
        unset($pc[$n]);
    }
}
$ddl->set('primary-constraint', $pc);

$ddl->saveToDir($dirname);

$c->end('DDL ACTUL+ mise à jour');