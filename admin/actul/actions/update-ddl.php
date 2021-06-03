<?php

$tables = [
    'ACT_ETAPE',
    'ACT_NOEUD',
    'ACT_LIEN',
    'ACT_VHENS_HEURES',
    'ACT_VHENS_GROUPES',
];

$filters = [
    'explicit'           => true,
    'table'              => ['includes' => $tables],
    'primary-constraint' => ['includes' => '%'],
    'package'            => ['includes' => 'OSE_ACTUL'],
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