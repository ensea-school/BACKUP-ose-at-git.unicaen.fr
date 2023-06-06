<?php

/* Initialisation */
$bdd = $oa->getBdd();
$bdd->setLogger($c);

/* Mise en place du schéma de la BDD */
$ref = new BddAdmin\Ddl\Ddl();
$ref->loadFromDir($oa->getOseDir() . 'data/ddl');
$bdd->create($ref);

/* Insertion des données */

// On s'occupe d'abord de créer puis d'initialiser l'utilisateur OSE et la source OSE
$bdd->dataUpdater()->run('install', 'UTILISATEUR');
$bdd->dataUpdater()->run('install', 'SOURCE');
$bdd->setOption('source-id', $oa->getSourceOseId());
$bdd->setOption('histo-user-id', $oa->getOseAppliId());

// On installe ensuite toutes les données
$bdd->dataUpdater()->run('install');

/* On construit les plafonds et les tableaux de bord */
$oa->exec('plafonds construire');

/* Définition d'un mdp pour oseappli */
if ($c->hasOption('oseappli-pwd')) {
    $pwd1 = $c->getOption('oseappli-pwd');
} else {
    $c->println("\n" . 'Choix d\'un mot de passe pour l\'utilisateur système oseappli', $c::COLOR_LIGHT_CYAN);
    $c->println("Veuillez saisir un mot de passe (au minimum 6 caractères) :");
    $pwd1 = $c->getSilentInput();

    $c->println("Veuillez saisir à nouveau le même mot de passe :");
    $pwd2 = $c->getSilentInput();

    if ($pwd1 <> $pwd2) {
        $c->printDie('Les mots de passe saisis ne correspondent pas!');
    }
}

$c->println('Application du mot de passe de oseappli...');
$oa->exec("changement-mot-de-passe --utilisateur=oseappli --mot-de-passe=$pwd1");

$c->println('Mot de passe changé', $c::COLOR_LIGHT_GREEN);

$c->println('Vous pourrez vous connecteur à OSE avec le login "oseappli" et votre nouveau mot de passe.');


/* Terminé!! */
$c->println('L\'installation de la base de données est maintenant terminée!', $c::COLOR_LIGHT_GREEN);