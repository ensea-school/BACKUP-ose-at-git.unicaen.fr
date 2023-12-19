<?php

// Initialisation
$bdd = $oa->getBdd();
$bdd->setLogger($c);

$c->begin("\nMise à jour des taux de mixité");

$c->println('Actualisation des taux ...');
$bdd->exec("BEGIN UNICAEN_IMPORT.SYNCHRONISATION('ELEMENT_TAUX_REGIMES', 'JOIN element_pedagogique ep ON ep.id = element_pedagogique_id WHERE import_action = ''update'' AND annee_id >= OSE_PARAMETRE.GET_ANNEE_IMPORT'); END;");

$c->println('Actualisation des éléments pédagogiques ...');
$bdd->exec("BEGIN UNICAEN_IMPORT.SYNCHRONISATION('ELEMENT_PEDAGOGIQUE'); END;");

$c->end('Mise à jour terminée');