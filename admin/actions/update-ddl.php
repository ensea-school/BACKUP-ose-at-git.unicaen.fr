<?php

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$filename = $oa->getOseDir().'data/ddl.php';

$ddlConfig = [
    'table'              => ['excludes' => 'UNICAEN_%'],
    'sequence'           => ['excludes' => 'UNICAEN_%'],
    'primary-constraint' => ['excludes' => 'UNICAEN_%'],
    'index'              => ['excludes' => 'UNICAEN_%'],
    'view'               => ['excludes' => ['SRC_%', 'V_DIFF_%', 'V_SYMPA_%', 'V_UNICAEN_%']],
    'materialized-view'  => ['includes' => ['MV_EXT_SERVICE', 'TBL_NOEUD']],
    'package'            => ['excludes' => ['UCBN_LDAP', 'UNICAEN_IMPORT_AUTOGEN_PROCS__']],
];
$ddl = $schema->getDdl($ddlConfig);
$schema->saveToFile($ddl, $filename);

$c->println('Fichier de DDL '.$filename.' Mis à jour');