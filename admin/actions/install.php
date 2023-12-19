<?php

$fromMaster = true;

$osedir = getcwd();

$c->println("Installation de OSE");

if (!$fromMaster) {
    if (!$c->hasOption('version')) {
        // Choix de la version
        $c->println("\nSélection de la version à déployer", $c::COLOR_LIGHT_CYAN);
        $c->println("Voici la liste des versions de OSE disponibles:");
        $tags = $oa->getTags();
        foreach ($tags as $tag) {
            $c->println($tag);
        }
        $c->print("Veuillez choisir une version à déployer: ");
    }
    $version = $c->getInput('version');
    if (!($oa->tagIsValid($version) || $oa->brancheIsValid($version))) {
        $c->printDie("$version n'est pas dans la liste des versions disponibles.");
    }


    // Récupération des sources
    $c->println("\nDéploiement à partir des sources GIT", $c::COLOR_LIGHT_CYAN);
    $tbr = $oa->tagIsValid($version) ? 'tags/' : '';
    $c->exec([
        "cd $osedir",
        "git checkout $tbr$version",
        "mkdir cache",
        "chmod 777 cache",
        "mkdir data/fichiers",
        "chmod 777 data/fichiers",
        "mkdir log",
        "chmod 777 log",
        "chmod +7 bin/ose",
    ]);
    $oa->writeVersion($version);
} else {
    $c->exec([
        "cd $osedir",
        "mkdir cache",
        "chmod 777 cache",
        "mkdir data/fichiers",
        "chmod 777 data/fichiers",
        "mkdir log",
        "chmod 777 log",
        "chmod +7 bin/ose",
    ]);
}

try {
    $e              = $c->exec('composer', false, true);
    $composerExists = true;
} catch (\Exception $e) {
    $composerExists = false;
}

if ($composerExists) {
    // Récupération des dépendances
    $c->println("\nChargement des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
    $c->println("\nPasser composer sur la version stable en cours et non la dev", $c::COLOR_LIGHT_CYAN);
    $c->passthru("cd $osedir;composer  self-update --2");
    $c->passthru("cd $osedir;composer install");
} else {
    // Récupération de Composer
    $c->println("\nRécupération de l'outil de gestion des dépendances Composer", $c::COLOR_LIGHT_CYAN);
    $c->passthru("cd $osedir;wget https://getcomposer.org/download/latest-2.x/composer.phar");

    // Récupération des dépendances
    $c->println("\nChargement des dépendances à l'aide de Composer", $c::COLOR_LIGHT_CYAN);
    $c->passthru("cd $osedir;php composer.phar install");
}

if (!file_exists($osedir . '/config.local.php')) {
    $c->exec([
        "cd $osedir",
        "cp config.local.php.default config.local.php",
    ]);
}

// Génération des proxies pour l'ORM Doctrine
$c->println("\nGénération des proxies pour l'ORM Doctrine", $c::COLOR_LIGHT_CYAN);
$c->exec([
    "cd $osedir",
    "php vendor/bin/doctrine-module orm:generate-proxies",
    "chmod -R 777 cache/DoctrineProxy",
    "chmod -R 777 cache/Doctrine",
]);

// Conclusion
$c->println("\nFin du script d'installation des fichiers", $c::COLOR_LIGHT_GREEN);
$c->println("Il reste encore plusieurs étapes à réaliser pour que OSE soit pleinement fonctionnel :");
$c->println(" 1 - Configurez le cas échéant votre serveur Apache");
$c->println(" 2 - Veuillez personnaliser le fichier de configuration de OSE config.local.php, si ce n'est déjà le cas");
$c->println(" 3 - La base de données devra au besoin être initialisée à l'aide de la commande ./bin/ose install-bdd");
$c->println(" 4 - Mettez en place les tâches CRON nécessaires (envoi de mails pour les indicateurs, Synchronisation automatique, etc.");
$c->println('');
$c->println("Pour la suite, merci de vous reporter au guide de l'administrateur pour vous aider à configurer l'application");
$c->println('');