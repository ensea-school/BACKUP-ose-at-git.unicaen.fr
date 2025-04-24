<?php

$ca = new ConnecteurPegase();
$ca->init();

$c->begin('Synchronisation de OSE à partir des données de Pegase');

$c->println('Lecture des données de la base source');
$ca->read();
$c->println('Transformation des données pour correspondre au formet de OSE');
$ca->adapt();
$c->println('Insertion des données dans la base OSE');
$ca->extractOdf();

$c->println('Synchronisation terminée');
$c->end('Caches à jour');


$c->end('Ose est maintenant à jour');