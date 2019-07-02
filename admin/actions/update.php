<?php

// Choix de la version
$c->println("Mise à jour de OSE");
$c->println("Assurez-vous bien d'avoir mis OSE en mode maintenance avant de démarrer\n(pressez Entrée pour continuer)...");
$c->getInput();

if (!$oa->bddIsOk()){
    $c->printDie('La mise à jour ne peut pas se poursuivre : la base de données est inaccessible.');
}

$osedir = $oa->getOseDir();

$c->exec([
    "cd $osedir",
    "git fetch --all --tags --prune",
], false);

$c->println("Sélection de la version à déployer", $c::COLOR_LIGHT_CYAN);
$c->println("La version actuellement installée est la " . $oa->oldVersion);
$c->println("Voici la liste des versions de OSE disponibles:");
$tags = $oa->getTags();
foreach ($tags as $tag) {
    $c->println($tag);
}
$ok = false;
while (!$ok) {
    $c->print("Veuillez choisir une version à déployer: ");
    $version = $c->getInput();
    if ($oa->tagIsValid($version)) {
        $ok = true;
    } else {
        $c->println("$version n'est pas dans la liste des versions disponibles.");
    }
}

// Récupération des sources
$c->println("\nMise à jour des fichiers à partir de GIT", $c::COLOR_LIGHT_CYAN);
$c->passthru([
    "cd $osedir",
    "git checkout tags/$version",
]);

// Récupération des dépendances
$c->println("\nMise à jour des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
$c->passthru([
    "cd $osedir",
    "php composer.phar self-update",
    "php composer.phar install",
]);

// Mise à jour des liens vers les répertoires publics des dépendances
$c->println("\nMise à jour des liens vers les répertoires publics des dépendances", $c::COLOR_LIGHT_CYAN);
$res = $oa->majUnicaenSymLinks($osedir);
$c->println($res ? 'Liens mis à jour' : 'Liens déjà à jour', $c::COLOR_GREEN);

// Conclusion
$oa->writeVersion($version);
$c->println("\nMise à jour des fichiers OK : la version installée est désormais la ".$version, $c::COLOR_LIGHT_GREEN);

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);
$oa->migration('pre');
$oa->run('update-bdd');
$oa->migration('post');

// Néttoyage des caches et mise à jour des proxies
$oa->run('clear-cache');

$c->println("\nFin de la mise à jour. N'oubliez pas de sortir du mode maintenance!");
$c->println('');