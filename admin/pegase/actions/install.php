<?php


$c->begin("\nInstallation du connecteur Pégase");

$c->println('Création de la source de données Pegase si besoin');
$sql = "BEGIN
  unicaen_import.add_source('Pegase', 'Pégase');
  commit;
END;";
$oa->getBdd()->exec($sql);


// Récupération du schéma de référence
$c->println('Mise en place des structures de données');
$ca = new ConnecteurPegase();
$ca->init();
$ddl = $ca->getDdl();

// On ne touche pas à autre chose que la partie Pegase!!
$filters = $ddl->filterOnlyDdl();

$tables = $oa->getBdd()->table()->get($filters->get('table'));
// Mise à jour de la BDD (structures)
foreach ($tables as $table => $values){
    $oa->getBdd()->getTable($table)->truncate();
}


$oa->getBdd()->alter($ddl, $filters);

$c->end('Connecteur installé');
$c->println('Le connecteur Pégase vers OSE est installé. Il vous reste à mettre en place par vous-même les vues sources et à les activer.');