<?php

$actions    = [
    "update"                    => "Mise à jour de l'application"
        . "\n\t--maintenance=no : pas de prompt pour être averti du mode maintenance"
        . "\n\t--version=<version cible> : Met à jour directement vers la version indiquée"
    ,
    "notifier-indicateurs"      => "Envoi des mails relatifs aux indicateurs",
    "synchronisation"           => "<job> : Effectue la synchronisation des données pour le <job> transmis",
    "chargens-calcul-effectifs" => "Calcul des effectifs du module Charges",
    "calcul-tableaux-bord"      => "Recalcule tous les tableaux de bord de calculs itermédiaires",
    "formule-calcul"            => "Calcul de toutes les heures complémentaires à l'aide de la formule",
    "creer-utilisateur"         => "Création d'un nouvel utilisateur de OSE. Possibilité de créer une fiche intervenant dans la foulée",
    "changement-mot-de-passe"   => "Changement de mot de passe (pour un utilisateur local uniquement)",
    "maj-public-links"          => "Mise à jour des liens vers les répertoires publics des dépendances",
    "clear-cache"               => "Vidage du cache de l'application",
    "test-bdd"                  => "Test d'accès à la base de données",
    "install-bdd"               => "Installe la base de données"
        . "\n\t--oseappli-pwd=<votre mdp> : mot de passe de l'utilisateur oseappli"
    ,
    "update-bdd"                => "Mise à jour de la base de données de l'application (sans les fichiers)",
];
$actionsDev = [
    "update-ddl"            => "Mise à jour du fichier de définition de la base de données à partir de cette dernière",
    "update-bdd-data"       => "Mise à jour des données de la base de données",
    "update-bdd-privileges" => "Mise à jour des privilèges de la base de données",
];

if ((getenv('APPLICATION_ENV') ?: 'dev') == 'dev') {
    $actions = array_merge($actions, $actionsDev);
}


$c->printMainTitle("OSE", 15);

$c->println('Actions possibles :');
$maxLength = 0;
foreach ($actions as $a => $l) {
    if (strlen($a) > $maxLength) $maxLength = strlen($a);
}
foreach ($actions as $a => $l) {
    $c->print($a, $c::COLOR_BLACK, $c::BG_LIGHT_GRAY);
    $c->print(str_pad('', $maxLength - strlen($a), ' '));
    $c->println(" : " . $l);
}