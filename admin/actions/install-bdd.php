<?php

/* Initialisation */
$c->println("\nInstallation de la base de données", $c::COLOR_LIGHT_CYAN);
if (!$oa->bddIsOk($msg)) {
    $c->printDie("Impossible d'accéder à la base de données : $msg!"
        ."\nVeuillez contrôler les paramètres de configurations entrés dans le fichier confg.local.php s'il vous plaît, avant de refaire une tentative d'installation de la base de données.");
}

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);



/* Mise en place du schéma de la BDD */
$c->println("\n" . 'Création des définitions de la base de données', $c::COLOR_LIGHT_PURPLE);

$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl.php');

$scl          = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);
$schema->create($ref, true);



/* Insertion des données */
$c->println("\n" . 'Insertion des données', $c::COLOR_LIGHT_PURPLE);
$dataGen = new DataGen($oa);
$dataGen->install();

$c->println("\n" . 'Mise à jour du point d\'indice pour les HETD', $c::COLOR_LIGHT_PURPLE);
$bdd->exec('BEGIN OSE_FORMULE.UPDATE_ANNEE_TAUX_HETD; END;');



/* Définition d'un mdp pour oseappli */
$c->println("\n" . 'Choix d\'un mot de passe pour l\'utilisateur système oseappli', $c::COLOR_LIGHT_CYAN);
$c->println("Veuillez saisir un mot de passe :");
$pwd1 = $c->getSilentInput();

$c->println("Veuillez saisir à nouveau le même mot de passe :");
$pwd2 = $c->getSilentInput();

if ($pwd1 <> $pwd2) {
    $c->printDie('Les mots de passe saisis ne correspondent pas!');
}

$c->println('Application du changement de mot de pase ...');
$oa->exec("changement-mot-de-passe --utilisateur=oseappli --mot-de-passe=$pwd1");

$c->println('Mot de passe changé', $c::COLOR_LIGHT_GREEN);

$c->println('Vous pourrez vous connecteur à OSE avec le login "oseappli" et votre nouveau mot de passe.');



/* Terminé!! */
$c->println('L\'installation de la base de données est maintenant terminée!', $c::COLOR_LIGHT_GREEN);