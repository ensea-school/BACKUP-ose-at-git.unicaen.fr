<?php

$actions = [
    "update"                    => "Mise à jour de l'application",
    "notifier-indicateurs"      => "Envoi des mails relatifs aux indicateurs",
    "synchronisation"           => "<job> : Effectue la synchronisation des données pour le <job> transmis",
    "chargens-calcul-effectifs" => "Calcul des effectifs du module Charges",
    "calcul-tableaux-bord"      => "Recalcule tous les tableaux de bord de calculs itermédiaires",
    "formule-calcul"            => "Calcul de toutes les heures complémentaires à l'aide de la formule",
    "changement-mot-de-passe"   => "Changement de mot de passe (pour un utilisateur local uniquement)",
    "maj-public-links"          => "Mise à jour des liens vers les répertoires publics des dépendances",
    "clear-cache"               => "Vidage du cache de l'application",
];

$c->println('Actions possibles :');
foreach ($actions as $a => $l) {
    $c->print($a, $c::COLOR_BLACK, $c::BG_LIGHT_GRAY);
    $c->println(" : " . $l);
}