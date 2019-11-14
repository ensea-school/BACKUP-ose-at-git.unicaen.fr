<?php

if (!$oa->bddIsOk($msg)) {
    $c->printDie("Impossible d'accéder à la base de données : $msg!"
        . "\nVeuillez contrôler vos paramètres de configuration s'il vous plaît, avant de refaire une tentative de MAJ de la base de données (./bin/ose update-bdd).");
}

$bdd    = $oa->getBdd();
$schema = new \BddAdmin\Schema($bdd);

$c->println("\nMise à jour des privilèges dans la base de données", $c::COLOR_LIGHT_CYAN);

$dataGen = new DataGen($oa);
$dataGen->updatePrivileges();