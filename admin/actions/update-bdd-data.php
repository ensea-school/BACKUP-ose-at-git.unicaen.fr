<?php

// Mise à jour
$dataGen = new DataGen($oa);
$dataGen->update();

// Néttoyage des caches
$oa->run('clear-cache');