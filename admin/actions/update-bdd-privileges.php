<?php

$oa->getBdd()->dataUpdater()->run('privileges');
$oa->exec('UnicaenCode GeneratePrivileges write=true');

// Néttoyage des caches
$oa->run('clear-cache');