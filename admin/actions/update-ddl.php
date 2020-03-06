<?php

// Initialisation
$schema = $oa->getBdd()->getSchema();

$filename = $oa->getOseDir() . 'data/ddl.php';

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
$ddl = $schema->getDdl($filters);
$ddl->saveToFile($filename);
$c->end('Fichier de DDL ' . $filename . ' Mis à jour');