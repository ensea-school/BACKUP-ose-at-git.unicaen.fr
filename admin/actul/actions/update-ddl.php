<?php

$tables = [
//    'ACT_ARBRE_ODF',
//    'ACT_DIPLOME',
//    'ACT_ELEMENT_COMMUN',
//    'ACT_ELEMENT_EFFECTIFS',
'ACT_ETAPE',
'ACT_ODF',
//    'ACT_ETAPE_EFFECTIFS',
//    'ACT_ODF_RELATIONS',
//    'ACT_OFFRE_DE_FORMATION',
//    'ACT_RESP_DIPLOME',
//    'ACT_RESP_ETP',
//    'ACT_RESP_VDI',
//    'ACT_RESP_VET',
//    'ACT_VDI_VET',
//    'ACT_LCC_APO',
//    'ACT_VET_EFFECTIFS',
//    'ACT_VOLUME_HORAIRE_ENS',
];

$views = [
//    'ACT_LIEN',
//    'ACT_NOEUD',
];

$filters = [
    'explicit'           => true,
    'table'              => ['includes' => $tables],
    'view'               => ['includes' => $views],
    'primary-constraint' => ['includes' => '%'],
];


$c->begin('Génération de la DDL du connecteur ACTUL+ à partir de la base de données');
$ddl = $oa->getBdd()->getDdl($filters);

/* On retire toutes les clés étrangères qui n'ont rien à voir avec ACTUL+ */
$pc = $ddl->get('primary-constraint');
foreach ($pc as $n => $d) {
    if (!in_array($d['table'], $tables)) {
        unset($pc[$n]);
    }
}
$ddl->set('primary-constraint', $pc);

$ca = new ConnecteurActul();
$ddl->saveToDir($ca->getDdlDir());

$c->end('DDL ACTUL+ mise à jour');