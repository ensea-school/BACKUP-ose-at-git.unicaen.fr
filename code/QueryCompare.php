<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$sql1 = "SELECT tbj.* FROM tbl_piece_jointe tbj 
         --where intervenant_id = 825820
        --JOIN intervenant i ON i.id = tbj.intervenant_id
        --JOIN piece_jointe pj ON pj.id = tbj.piece_jointe_id";

$sql2 = "SELECT  tbjo.* FROM tbl_piece_jointe_old2 tbjo
        --where intervenant_id = 825820
       -- JOIN intervenant i ON i.id = tbjo.intervenant_id
        --JOIN piece_jointe pj ON pj.id = tbjo.piece_jointe_id";


$keyCols = ['TYPE_PIECE_JOINTE_ID', 'INTERVENANT_ID', 'ANNEE_ID'];

//pays_nationalite => paysNationalite
//date_creation => dateCreation
//date_contrat_lie => dateContratLie

$ignoreCols = [
    'DEMANDEE_APRES_RECRUTEMENT',
    'DATE_ORIGINE',
    'DATE_VALIDITEE',
    'ID',
    'PIECE_JOINTE_ID',
    'HEURES_POUR_SEUIL',

    // colonne supprimée
];

$onlyCols = [
    //'avenant1'
    //'contrat1',
    //'numeroAvenant'
];

$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);

$comparator = new \Unicaen\BddAdmin\Tools\Comparator($bdd);

$comparator->setQuery1($sql1);
$comparator->setQuery2($sql2);
$comparator->setKeyColumns($keyCols);
$comparator->setIgnoreColumns($ignoreCols);
$comparator->setOnlyColumns($onlyCols);

//$comparator->setLimit(100);

$result = $comparator->run();

echo $comparator->html($result);
