<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$c = $container->get(\UnicaenTbl\Service\TableauBordService::class);
$bdd = $container->get(\Unicaen\BddAdmin\Bdd::class);


$tbls = [
    'chargens_seuils_def',
//    'chargens',
//    'formule',
    'candidature',
    'dmep_liquidation',
    'piece_jointe_demande',
    'piece_jointe_fournie',
    'agrement',
    'cloture_realise',
    'contrat',
    'dossier',
    'paiement',
    'piece_jointe',
    'referentiel',
    'validation_enseignement',
    'validation_referentiel',
    'service',
    'mission',
//    'workflow',
    'plafond_structure',
    'plafond_intervenant',
    'plafond_element',
    'plafond_volume_horaire',
    'plafond_referentiel',
    'plafond_mission',
];

$tbls = ['workflow'];

$params = [
    //'ETAPE_ID' => 1367
];


$vider = true;
$vider = false;

echo '<pre>';
foreach( $tbls as $tblName ){
    echo "\n\n";

    echo "### $tblName ###\n";

    if ($vider){
        $bdd->exec("truncate table tbl_".$tblName);
    }

    $countTable = $bdd->select('SELECT count(*) CC FROM tbl_'.$tblName)[0]['CC'];
    echo "  Table avant calcul : $countTable lignes\n";

    $tbl = $c->getTableauBord($tblName);



    $debut = microtime(true);


    $tbl->calculer($params);

    $fin = microtime(true);
    $duree = $fin - $debut;

    echo "  Durée : ".round($duree, 4) . ' seconde(s)'."\n";

    $countTable = $bdd->select('SELECT count(*) CC FROM tbl_'.$tblName)[0]['CC'];
    $countView = '';
  //  $countView = $bdd->select('SELECT count(*) CC FROM v_tbl_'.$tblName)[0]['CC'];

    echo "  View : $countView lignes\n";
    echo "  Table après calcul : $countTable lignes\n";
}
echo "\n\n".'</pre>';
