<?php

use Unicaen\BddAdmin\Ddl\Ddl;
use Application\Controller\WorkflowController;
use Plafond\Service\PlafondService;

// Initialisation
$bdd = $oa->getBdd();

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);
$c->println("\n" . 'Mise à jour des définitions de la base de données. Merci de patienter ...', $c::COLOR_LIGHT_PURPLE);


// Récupération du schéma de référence
$ref = $bdd->getRefDdl();


// Construction de la config de DDL pour filtrer
$filters = require getcwd() . '/data/ddl_config.php';
foreach ($ref as $ddlClass => $objects) {
    foreach ($objects as $object => $objectDdl) {
        $filters[$ddlClass]['includes'][] = $object;
    }
}

if (($_SERVER['IGNORE_MV_EXT_SERVICE'] ?? "false") == "true") {
    $filters['materialized-view']['excludes'][] = 'MV_EXT_SERVICE';
}

$tablesDep = [
    Ddl::INDEX,
    Ddl::PRIMARY_CONSTRAINT,
    Ddl::REF_CONSTRAINT,
    Ddl::UNIQUE_CONSTRAINT,
];

foreach ($tablesDep as $tableDep) {
    $objects = $bdd->manager($tableDep)->get();
    foreach ($objects as $obj) {
        if (in_array($obj['table'], $filters['table']['includes'])) {
            $filters[$tableDep]['includes'][] = $obj['name'];
        }
    }
}


// Initialisation et lancement de la pré-migration
$mm = new MigrationManager($oa, $ref, $filters);
$mm->migration('before');


// Mise à jour de la BDD (structures)
$bdd->alter($ref, $filters, true);


// Mise à jour des séquences
$bdd->majSequences($ref);


// Mise à jour des données
$bdd->logBegin('Contrôle et mise à jour des données');
$bdd->dataUpdater()->run('update');
$bdd->logEnd('Données à jour');


// Mise à jour du cache des structures
$bdd->exec('BEGIN OSE_DIVERS.UPDATE_STRUCTURES(); END;');


$args = 'plafonds construire';
$c->passthru("php " . getcwd() . "/public/index.php " . $args);

/** @var PlafondService $servicePlafond */
$servicePlafond = $oa->container()->get(PlafondService::class);
$servicePlafond->construire();

/** @var WorkflowController $wf */
$c->begin('Mise à jour des tableaux de bords');
$wf = $oa->getController(WorkflowController::class);
$wf->calculTableauxBordAction();

$c->end();


// Post-migration
$c->println('');
$mm->migration('after');

// Néttoyage des caches
$oa->run('clear-cache');
