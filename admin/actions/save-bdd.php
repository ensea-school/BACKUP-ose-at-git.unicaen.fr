<?php

$bdd = $oa->getBdd();

$filename = $c->getArg()[2];

$c->println("Sauvegarde d'une base de données", $c::COLOR_CYAN);

$fichiers = true;
if ($c->hasOption('fichiers')) {
    $fichiers = $c->getInput('fichiers', "Les contenus de la table FICHIER doivent-ils être inclus ? (O/N)", 'bool');
}

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

$bdd->save($filename, [], $fncs);