<?php

// Initialisation
$bdd = $oa->getBdd();

// Recalcul des vues matérialisées
$bdd->refreshMaterializedViews();