<?php

// Mise à jour
$dataGen = new DataGen($oa);
$dataGen->updatePrivileges();

$oa->exec('UnicaenCode GeneratePrivileges write=true');

// Néttoyage des caches
$oa->run('clear-cache');