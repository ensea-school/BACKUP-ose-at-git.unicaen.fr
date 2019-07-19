<?php

if (!$oa->bddIsOk($msg)) {
    $c->printDie("Impossible d'accéder à la base de données : $msg!"
        ."\nVeuillez contrôler vos paramètres de configuration s'il vous plaît, avant de refaire une tentative de MAJ de la base de données (./bin/ose update-bdd).");
}

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);

$oa->migration('pre');

$c->println("\n" . 'Mise à jour des définitions de la base de données', $c::COLOR_LIGHT_PURPLE);

/* Récupération du schéma de référence */
$ref = $schema->loadFromFile($oa->getOseDir() . 'data/ddl.php');


/* Construction de la config de DDL pour filtrer */
$ddlConfig = require $oa->getOseDir() . 'data/ddl_config.php';
$classes   = [
    // Tous les objets de ces classes seront intégralement pris en compte dans la MAJ
    \BddAdmin\Ddl\DdlView::class,
    \BddAdmin\Ddl\DdlPackage::class,
    \BddAdmin\Ddl\DdlTrigger::class,
    \BddAdmin\Ddl\DdlSequence::class,
];

foreach ($classes as $ddlClass) {
    if (isset($ref[$ddlClass])) {
        $objects = array_keys($ref[$ddlClass]);
        foreach ($objects as $object) {
            $ddlConfig[$ddlClass]['includes'][] = $object;
        }
    }
}


/* Mise en place du logging en mode console */
$scl          = new \BddAdmin\SchemaConsoleLogger();
$scl->console = $c;
$schema->setLogger($scl);

/* Mise à jour de la BDD */
$schema->alter($ref, $ddlConfig, true);

/* Mise à jour des séquences */
$c->println("\n".'Mise à jour des séquences', $c::COLOR_LIGHT_PURPLE);
$schema->majSequences($ref);

$c->println("\n".'Contrôle et mise à jour des données', $c::COLOR_LIGHT_PURPLE);
$dataGen = new DataGen($oa);
$dataGen->update();

$c->println('');
$oa->migration('post');

$c->println("\n".'Mise à jour du point d\'indice pour les HETD', $c::COLOR_LIGHT_PURPLE);
$bdd->exec('BEGIN OSE_FORMULE.UPDATE_ANNEE_TAUX_HETD; END;');