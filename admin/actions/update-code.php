<?php

// Initialisation
$osedir = $oa->getOseDir();


// Choix de la version
$c->exec([
    "cd $osedir",
    "git fetch --all --tags --prune",
], false);

if (!$c->hasOption('version')) {
    $c->println("Sélection de la version à déployer", $c::COLOR_LIGHT_CYAN);
    $c->println("La version actuellement installée est la " . $oa->oldVersion);
    $c->println("Voici la liste des versions de OSE disponibles:");
    $tags = $oa->getTags();
    foreach ($tags as $tag) {
        $c->println($tag);
    }

    // Choix de la version
    $c->print("Veuillez choisir une version à déployer: ");
}
$version = $c->getInput('version');
if (!($oa->tagIsValid($version) || $oa->brancheIsValid($version))) {
    $c->printDie("$version n'est pas dans la liste des versions disponibles.");
}


// Récupération des sources
$c->println("\nMise à jour des fichiers à partir de GIT", $c::COLOR_LIGHT_CYAN);
$tbr = $oa->tagIsValid($version) ? 'tags/' : '';
if ($version == $oa->getCurrentBranche()) {
    $updcmd = 'git pull';
} else {
    $updcmd = "git checkout $tbr$version";
}
$c->passthru([
    "cd $osedir",
    $updcmd,
]);

$oa->writeVersion($version);
$c->println("\nMise à jour du code source OK : la version installée est désormais la " . $version, $c::COLOR_LIGHT_GREEN);


// Récupération des dépendances
$c->println("\nMise à jour des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
$c->passthru([
    "export COMPOSER_ALLOW_SUPERUSER=1",
    "cd $osedir",
    "php composer.phar self-update --2.2",
    "php composer.phar install --optimize-autoloader",
]);
