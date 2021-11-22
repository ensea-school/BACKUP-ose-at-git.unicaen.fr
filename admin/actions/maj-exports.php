<?php

// Initialisation
$bdd = $oa->getBdd();
$bdd->setLogger($c);

// Recalcul des vues matérialisées
$bdd->refreshMaterializedViews();