<?php

// Mise à jour
$dataGen = new DataGen($oa);
$dataGen->updatePrivileges();

// Néttoyage des caches
$oa->run('clear-cache');