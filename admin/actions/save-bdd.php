<?php

$bdd = $oa->getBdd();

$c->println("Sauvegarde d'une base de données", $c::COLOR_CYAN);

if (!$c->hasOption('dir')) {
    $c->print("Veuillez entrer le répertoire où seront enregistrées les données : ");
}
$dir = $c->getInput('dir');

$fichiers = $c->getInput('fichiers', "Les contenus de la table FICHIER doivent-ils être inclus ? (O/N)", 'bool');

$fncs = [
    'SYNC_LOG' => function ($d) {
        return null;
    },
];
if (!$fichiers) {
    $fncs['FICHIER'] = function ($d) {
        $d['TAILLE']  = 0;
        $d['CONTENU'] = null;

        return $d;
    };
}

$bdd->save($dir, [], $fncs);