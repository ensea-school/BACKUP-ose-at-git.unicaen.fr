<?php

$dirname    = $oa->getOseDir() . 'data/ddl';
$colPosFile = $oa->getOseDir() . 'data/ddl_columns_pos.php';

$filters = [
    'table'              => ['excludes' => ['UNICAEN_ELEMENT_DISCIPLINE', 'UNICAEN_CORRESP_STRUCTURE_CC', 'SYS_EXPORT_SCHEMA_%', 'ACT_%']],
    'sequence'           => ['excludes' => ['UNICAEN_CORRESP_STRUCTU_ID_SEQ']],
    'primary-constraint' => ['excludes' => ['UNICAEN_CORRESP_STR_CC_PK', 'UNICAEN_ELEMENT_DISCIPLINE_PK', 'ACT_%']],
    'index'              => ['excludes' => ['UNICAEN_CORRESP_STR_CC_PK', 'UNICAEN_ELEMENT_DISCIPLINE_PK', 'ACT_%']],
    'view'               => ['excludes' => ['SRC_%', 'V_DIFF_%', 'V_SYMPA_%', 'V_UNICAEN_OCTOPUS_TITULAIRES', 'V_UNICAEN_OCTOPUS_VACATAIRES']],
    'materialized-view'  => ['includes' => [
        'MV_EXT_SERVICE',
        'MV_EXT_DOTATION_LIQUIDATION',
        'MV_EXT_ETAT_PAIEMENT',
        'MV_LIEN',
    ]],
    'package'            => ['excludes' => ['UCBN_LDAP', 'UNICAEN_IMPORT_AUTOGEN_PROCS__', 'OSE_ACTUL']],
];


$c->begin('Génération du fichier de DDL à partir de la base de données');
$ddl = $oa->getBdd()->getDdl($filters);


/* Traitement des positionnement de colonnes */
if (file_exists($colPosFile)) {
    $positions = require_once $colPosFile;
} else {
    $positions = [];
}

$positions = $ddl->applyColumnPositions($positions);
$ddl->writeArray($colPosFile, $positions);
$c->end('Positionnement de colonnes à jour');


$ddl->saveToDir($dirname);
$c->end('Définition Fichier de la base de données mise à jour');