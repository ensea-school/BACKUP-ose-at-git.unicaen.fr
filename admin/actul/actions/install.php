<?php

use BddAdmin\Ddl\Ddl;

$ca = new ConnecteurActul();

$c->println("\nInstallation ou mise à jour du connecteur ACTUL+", $c::COLOR_LIGHT_CYAN);

// Récupération du schéma de référence
$ddl = new Ddl();
$ddl->loadFromDir($ca->getDdlDir());

// On ne touche pas à autre chose que la partie ACTUL!!
$filters = $ddl->filterOnlyDdl()->toArray();

// Mise à jour de la BDD (structures)
$oa->getBdd()->alter($ddl, $filters);
