<?php

$dirname    = $oa->getOseDir() . 'data/ddl';
$colPosFile = $oa->getOseDir() . 'data/ddl_columns_pos.php';

$filters = [
    'table'              => ['excludes' => ['UNICAEN_%', 'SYS_EXPORT_SCHEMA_%', 'ACT_%']],
    'sequence'           => ['excludes' => ['UNICAEN_%']],
    'primary-constraint' => ['excludes' => ['UNICAEN_%', 'ACT_%']],
    'index'              => ['excludes' => ['UNICAEN_%', 'ACT_%']],
    'view'               => ['excludes' => ['SRC_%', 'V_DIFF_%', 'V_SYMPA_%', 'V_UNICAEN_%']],
    'materialized-view'  => ['includes' => [
        'MV_EXT_SERVICE',
        'MV_EXT_DOTATION_LIQUIDATION',
        'MV_EXT_ETAT_PAIEMENT',
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

$sql = "SELECT 
  tbl.table_name,
  tc.column_name,
  COALESCE(vc.column_id,0) + 1 position  
FROM 
  tbl
  JOIN user_tab_columns tc ON tc.table_name = tbl.table_name
  LEFT JOIN user_tab_columns vc ON vc.table_name = tbl.view_name AND tc.column_name = vc.column_name
WHERE 
  tbl.table_name IS NOT NULL AND tbl.view_name IS NOT NULL
ORDER BY
  tbl.view_name,
  position";
$dd  = $oa->getBdd()->select($sql);
foreach ($dd as $d) {
    $positions[$d['TABLE_NAME']][$d['POSITION'] - 1] = $d['COLUMN_NAME'];
}

$positions = $ddl->applyColumnPositions($positions);
$ddl->writeArray($colPosFile, $positions);
$c->end('Positionnement de colonnes à jour');


$ddl->saveToDir($dirname);
$c->end('Définition Fichier de la base de données mise à jour');