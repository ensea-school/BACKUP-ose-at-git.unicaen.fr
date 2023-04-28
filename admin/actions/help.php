<?php

$actions    = [
    "install"                   => "Installation d'une nouvelle instance"
        . "\n\t--version=<version cible> : Met à jour directement vers la version indiquée"
    ,
    "update"                    => "Mise à jour de l'application"
        . "\n\t--maintenance=no : pas de prompt pour être averti du mode maintenance"
        . "\n\t--version=<version cible> : Met à jour directement vers la version indiquée"
    ,
    "notifier-indicateurs"      => "Envoi des mails relatifs aux indicateurs",
    "synchronisation"           => "<job> : Effectue la synchronisation des données pour le <job> transmis",
    "chargens-calcul-effectifs" => "Calcul des effectifs du module Charges",
    "calcul-tableaux-bord"      => "Recalcule tous les tableaux de bord de calculs intermédiaires",
    "formule-calcul"            => "Calcul de toutes les heures complémentaires à l'aide de la formule",
    "creer-utilisateur"         => "Création d'un nouvel utilisateur de OSE. Possibilité de créer une fiche intervenant dans la foulée",
    "changement-mot-de-passe"   => "Changement de mot de passe (pour un utilisateur local uniquement)",
    "maj-taux-mixite"           => "Met à jour les taux de mixité",
    "maj-exports"               => "Mise à jour des vues matérialisées utilisées pour les exports BO/SID",
    "clear-cache"               => "Vidage du cache de l'application",
    "test-bdd"                  => "Test d'accès à la base de données",
    "install-bdd"               => "Installe la base de données"
        . "\n\t--oseappli-pwd=<votre mdp> : mot de passe de l'utilisateur oseappli"
    ,
    "update-code"               => "Mise à jour du code source de l'application (sans toucher à la BDD)",
    "update-bdd"                => "Mise à jour de la base de données de l'application (sans les fichiers)",
    "update-employeur"          => "Mise à jour de la table employeur à partir d'une source (par défault source INSEE)",
    "build-synchronisation"     => "Reconstruction des vues différentielles et des procédures de mise à jour",
    "fichiers-vers-filesystem"  => "Déplace le contenu des fichiers (table FICHIER) vers le système de fichiers",
    "save-bdd <Fichier cible>"  => "Sauvegarde une base de données dans un fichier"
        . "\n\t--fichiers=n : Ignore le contenu des fichiers stockée dans la table FICHIER",
    "load-bdd <Fichier source>" => "Charge une base de données à partir d'un fichier de sauvegarde",
];
$actionsDev = [
    "update-ddl"            => "Mise à jour du fichier de définition de la base de données à partir de cette dernière",
    "update-bdd-data"       => "Mise à jour des données de la base de données",
    "update-bdd-privileges" => "Mise à jour des privilèges de la base de données",
    "update-bdd-formules"   => "Mise à jour de la liste des formules de calcul",
    "test"                  => "Script de tests divers",
    "test-migration"        => "Script de test de migration. Paramètres : [before|after nom_du_script_de_migration]",
    "build-tableaux-bord"   => "Reconstruction des tableaux de bord",
];

if ($this->inDev()) {
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