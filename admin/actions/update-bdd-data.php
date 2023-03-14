<?php

// Mise à jour
$oa->getBdd()->dataUpdater()->run('update');

// Néttoyage des caches
$oa->run('clear-cache');