<?php

$c->begin("\nInstallation ou mise à jour du connecteur ACTUL+");

$c->println('Création de la source de données Actul+ si besoin');
$sql = "BEGIN
  unicaen_import.add_source('Actul', 'Actul +');
  commit;
END;";
$oa->getBdd()->exec($sql);


// Récupération du schéma de référence
$c->println('Mise en place des structures de données');
$ca = new ConnecteurActul();
$ca->init();
$ddl = $ca->getDdl();

// On ne touche pas à autre chose que la partie ACTUL!!
$filters = $ddl->makeFilters();

// Mise à jour de la BDD (structures)
$oa->getBdd()->alter($ddl, $filters);

$c->end('Connecteur installé');
$c->println('Le connecteur Actul+ vers OSE est installé. Il vous reste à mettre en place par vous-même les vues sources et à les activer.');