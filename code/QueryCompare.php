<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$sql1 = "SELECT * FROM tbl_piece_jointe";

$sql2 = "SELECT * FROM v_tbl_piece_jointe";

$keyCols = ['TYPE_PIECE_JOINTE_ID', 'INTERVENANT_ID'];

//pays_nationalite => paysNationalite
//date_creation => dateCreation
//date_contrat_lie => dateContratLie

$ignoreCols = [
    'DEMANDEE_APRES_RECRUTEMENT',
    'DATE_ORIGINE',
    'DATE_VALIDITEE',
    'SEUIL_HETD',
    'HEURES_POUR_SEUIL',
    'ID',

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

$comparator->setLimit(5000);

$result = $comparator->run();

echo $comparator->html($result);
