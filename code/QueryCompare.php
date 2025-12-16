<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$sql1 = "SELECT * FROM tbl_paiement p 
        where intervenant_id in (1375,1403,1462,2020,2058,2116,2283,2331,
4400,4766,5204,6307,6559,6725,7179,7237,7267,7272,
10491,10638,10649,11334,11485,12674,13019,13020,13547,14391,
16752,16789,17125,17182,18713,18797,18840,18860,19168,
59034,
163996,164688,
192862,193699,193934,
639035,658968,
783046,
826610)
        --where intervenant_id = 825820        
        --JOIN intervenant i ON i.id = tbj.intervenant_id
        --JOIN piece_jointe pj ON pj.id = tbj.piece_jointe_id";

$sql2 = "SELECT * FROM save_tbl_paiement p
        where intervenant_id in (1375,1403,1462,2020,2058,2116,2283,2331,
4400,4766,5204,6307,6559,6725,7179,7237,7267,7272,
10491,10638,10649,11334,11485,12674,13019,13020,13547,14391,
16752,16789,17125,17182,18713,18797,18840,18860,19168,
59034,
163996,164688,
192862,193699,193934,
639035,658968,
783046,
826610)
        --where intervenant_id = 825820
        -- JOIN intervenant i ON i.id = tbjo.intervenant_id
        --JOIN piece_jointe pj ON pj.id = tbjo.piece_jointe_id";


$keyCols = ['ANNEE_ID','SERVICE_ID',
    'SERVICE_REFERENTIEL_ID',
    'MISSION_ID',
    'TYPE_HEURES_ID',
    'INTERVENANT_ID',
    'PERIODE_ENS_ID',
    'MISE_EN_PAIEMENT_ID',
    'TAUX_REMU_ID',
    'TAUX_HORAIRE',];

//pays_nationalite => paysNationalite
//date_creation => dateCreation
//date_contrat_lie => dateContratLie

$ignoreCols = [
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

//$comparator->setLimit(100);

$result = $comparator->run();

echo $comparator->html($result);
