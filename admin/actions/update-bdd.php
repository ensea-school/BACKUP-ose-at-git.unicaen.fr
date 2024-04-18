<?php

use Application\Controller\WorkflowController;
use Plafond\Service\PlafondService;
use Unicaen\BddAdmin\Ddl\Ddl;
use Unicaen\BddAdmin\Bdd;

// Initialisation
$bdd = $oa->getBdd();

$c->println("\nMise à jour de la base de données", $c::COLOR_LIGHT_CYAN);
$c->println("\n" . 'Mise à jour des définitions de la base de données. Merci de patienter ...', $c::COLOR_LIGHT_PURPLE);


// Récupération du schéma de référence
$ref = $bdd->getRefDdl();

// script provisoire pour traduire la DDL en Postgresql
/** @TODO à retirer une fois la migration effectuée */
$o2p = new \Unicaen\BddAdmin\Tools\Oracle2Postgresql();
$o2p->translateDdl($ref);

$filters = $ref->makeFilters();
$filters->addArray(require getcwd() . '/data/ddl_config.php');

$bdd->alter($ref, $filters);

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

// Néttoyage des caches
$oa->run('clear-cache');