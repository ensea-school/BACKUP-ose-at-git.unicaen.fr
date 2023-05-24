<?php

// Mise à jour
$oa->getBdd()->dataUpdater()->run('install', 'TAUX_REMU');
$oa->getBdd()->dataUpdater()->run('install', 'TAUX_REMU_VALEUR');
$oa->getBdd()->dataUpdater()->run('install', 'TYPE_MISSION');
