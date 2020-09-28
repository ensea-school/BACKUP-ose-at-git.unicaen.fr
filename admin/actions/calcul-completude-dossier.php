<?php


$c->print("Veuillez saisir l'année :");
$annee = $c->getInput();

$c->print("Veuillez saisir un id d'intervenant, si vide tous les intervenants de l'année seront pris en compte :");
$intervenant = $c->getInput();

if (!$annee && !$intervenant) {
    $c->println("Aucun Paramètre");
} elseif (!$annee || ($intervenant && $annee)) {
    $c->println("Calcul de la complétude du dossier de l'intervenant : " . $intervenant);
    $oa->exec("calcul-completude-dossier --intervenant=$intervenant");
    $c->println("Complétude du dossier recalculée");
} elseif (!$intervenant) {
    $c->println("Calcul de la complétude de tous les dossiers de l'année : " . $annee);
    $oa->exec("calcul-completude-dossier --annee=$annee");
    $c->println("Complétude des dossiers recalculée");
}

