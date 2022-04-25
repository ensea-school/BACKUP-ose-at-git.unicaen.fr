<?php

$ca = new ConnecteurActul;
$ca->init();

$c->begin('Mise à jour de OSE à partir des données d\'Actul +');


$at = $ca->getActTables();
$c->println('');
$c->begin('Transfert des données depuis Actul');
foreach ($at as $table) {
    $c->print('Table ' . $table . '... ');
    $ca->majActTable($table);
    $c->println('OK');
}
$c->end('Transfert terminé');


$st = $ca->getSyncTables();
$c->println('');
$c->begin('Synchronisation des données vers OSE');
foreach ($st as $table) {
    $c->print('Table ' . $table . '... ');
    $ca->syncTable($table);
    $c->println('OK');
}
$c->end('Synchronisation terminée');


$c->begin('Mise à jour des caches de données');
$c->print('Vues matérialisées pour les charges d\'enseignement ... ');
$ca->ose->exec('BEGIN OSE_CHARGENS.MAJ_CACHE; END;');

$c->println('OK');

$oa->run('chargens-calcul-effectifs', true);

$c->print('Tableau de bord des charges ... ');
$ca->ose->exec('BEGIN UNICAEN_TBL.CALCULER(\'chargens\'); END;');
$c->println('OK');
$c->end('Caches à jour');


$c->end('Ose est maintenant à jour');