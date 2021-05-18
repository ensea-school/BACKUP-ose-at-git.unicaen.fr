<?php

$ca = new ConnecteurActul;
$ca->init();

$table = 'ACT_ELEMENT_COMMUN';

$tables = $ca->getTables();

foreach ($tables as $table) {
    try {
        $c->print('Mise à jour de la table tampon ' . $table . ' ... ');
        $res = $ca->majTampon($table, 2022);
        if (0 == $res) {
            $c->println('pas de données à mettre à jour', $c::COLOR_GREEN);
        } elseif (1 == $res) {
            $c->println($res . ' ligne mise à jour', $c::COLOR_GREEN);
        } else {
            $c->println($res . ' lignes mises à jour', $c::COLOR_GREEN);
        }
    } catch (\Exception $e) {
        $c->println('ERREUR !', $c::COLOR_LIGHT_RED);
        $c->println($e->getMessage(), $c::COLOR_LIGHT_RED);
    }
}





/*
act_arbre_odf ??
 */



