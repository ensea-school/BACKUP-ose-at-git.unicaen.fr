<?php

// Mise à jour
$bdd = $oa->getBdd();

$bdd->logBegin('Contrôle et mise à jour des données');
$bdd->dataUpdater()->run('update');
$bdd->logEnd('Données à jour');

// Néttoyage des caches
$oa->run('clear-cache');