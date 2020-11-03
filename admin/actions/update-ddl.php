<?php

$dirname    = $oa->getOseDir() . 'data/ddl';
$colPosFile = $oa->getOseDir() . 'data/ddl_columns_pos.php';

$filters = [
    'table'              => ['excludes' => 'UNICAEN_%'],
    'sequence'           => ['excludes' => 'UNICAEN_%'],
    'primary-constraint' => ['excludes' => 'UNICAEN_%'],
    'index'              => ['excludes' => 'UNICAEN_%'],
    'view'               => ['excludes' => ['SRC_%', 'V_DIFF_%', 'V_SYMPA_%', 'V_UNICAEN_%']],
    'materialized-view'  => ['includes' => [
        'MV_EXT_SERVICE',
        'MV_EXT_DOTATION_LIQUIDATION',
        'MV_EXT_ETAT_PAIEMENT',
        'TBL_NOEUD',
    ]],
    'package'            => ['excludes' => ['UCBN_LDAP', 'UNICAEN_IMPORT_AUTOGEN_PROCS__']],
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