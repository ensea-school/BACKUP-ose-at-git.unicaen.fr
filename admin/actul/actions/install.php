<?php

use BddAdmin\Ddl\Ddl;

$ca = new ConnecteurActul();

$c->println("\nInstallation ou mise à jour du connecteur ACTUL+", $c::COLOR_LIGHT_CYAN);

// Récupération du schéma de référence
$ca->init();
$ddl = $ca->getDdl();

// On ne touche pas à autre chose que la partie ACTUL!!
$filters = $ddl->filterOnlyDdl();

// Mise à jour de la BDD (structures)
$oa->getBdd()->alter($ddl, $filters);

// Création de la source ACTUL
$sql = "BEGIN
  unicaen_import.add_source('Actul', 'Actul +');
  commit;
END;";
$oa->getBdd()->exec($sql);