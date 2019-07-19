<?php

if (!$oa->bddIsOk($msg)) {
    $c->printDie("Impossible d'accéder à la base de données : $msg!"
        ."\nVeuillez contrôler les paramètres de configurations entrés dans le fichier confg.local.php s'il vous plaît, avant de refaire une tentative d'installation de la base de données.");
}

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$c->println("\nInstallation de la base de données", $c::COLOR_LIGHT_CYAN);

$c->println("\n" . 'Création des définitions de la base de données', $c::COLOR_LIGHT_PURPLE);

/* Récupération du schéma de référence */
$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl.php');


/* Mise en place du logging en mode console */
$scl          = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);

/* Mise à jour de la BDD */
$schema->create($ref, true);

$c->println("\n" . 'Insertion des données', $c::COLOR_LIGHT_PURPLE);
$dataGen = new DataGen($oa);
$dataGen->update();

$c->println("\n" . 'Mise à jour du point d\'indice pour les HETD', $c::COLOR_LIGHT_PURPLE);
$bdd->exec('BEGIN OSE_FORMULE.UPDATE_ANNEE_TAUX_HETD; END;');
