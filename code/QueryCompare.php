<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$sql1 = "SELECT * FROM v_contrat_main_old";

$sql2 = "SELECT * FROM v_contrat_main";

$keyCols = ['CONTRAT_ID'];

//pays_nationalite => paysNationalite
//date_creation => dateCreation
//date_contrat_lie => dateContratLie

$ignoreCols = [
    'FORMULE_RESULTAT_ID', // colonne supprimée
    'totalDiviseParDix',   // colonne supprimée
    'tauxId',              // colonne supprimée
    'tauxMajoreId',        // colonne supprimée

    'heuresPeriodeEssai',   // nouvelle colonne
    'heuresPrimePrecarite', // nouvelle colonne

    'horodatage',          // dates générées à partir de NOW => pas de pb

    'date_creation',         // formatage pourri ancienne => Ok maintenant
    'date_contrat_lie',      // formatage pourri ancienne => Ok maintenant
    'tauxHoraireDate',       // formatage pourri ancienne => Ok maintenant
    'tauxMajoreHoraireDate', // colonne vide avant

    'heuresFormation', // toutes vides ancienne vue

    'enteteAutresHeures',  // problèmes trouvés dans ancienne vue v_contrat_main
    'legendeAutresHeures', // problèmes trouvés dans ancienne vue v_contrat_main

    'pays_nationalite', // colonnes vides dans ancienne, car on ne prenait en compte que les données perso sans tenir compte de la table intervenant

    'serviceTotalPaye', // le nouvel calcul met le taux de CP à 0 pour les enseignements, pas l'ancienne vue

    /* à contrôler + finement */
    'titreCourt', // lié au pb parentalité des avenants ??
    'totalHETD',  // arrondis à retester ?
    //avenant1	contrat1	modifieComplete	n	titre reste => avenants qui se transforment en contrat et vice versa ??
    // contrats concernés : 2479, 40336, 36972, 36028, 19628, 1912, 1854

];

$onlyCols = [
    //'hetdContrat'
];

$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

$comparator = new \Unicaen\BddAdmin\Tools\Comparator($bdd);

$comparator->setQuery1($sql1);
$comparator->setQuery2($sql2);
$comparator->setKeyColumns($keyCols);
$comparator->setIgnoreColumns($ignoreCols);
$comparator->setOnlyColumns($onlyCols);

$comparator->setLimit(1000);

$result = $comparator->run();

echo $comparator->html($result);
