<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

use Unicaen\BddAdmin\Bdd;
use Formule\Service\FormuleService;
use Formule\Service\TestService;
use Formule\Model\Arrondisseur\Testeur;
use Formule\Entity\FormuleIntervenant;

$bdd = $container->get(Bdd::class);

$testTablename = 'TEST_ARR';

if (!$bdd->table()->exists($testTablename)) {
    $sql = "create table test_arr as 
    select 
      i.annee_id, 
      fri.intervenant_id, 
      fri.type_volume_horaire_id, 
      fri.etat_volume_horaire_id, 
      0 passe,
      0 err_none, 
      0 err_minimal, 
      0 err_full, 
      0 err_custom_min, 
      0 err_custom_full 
    from formule_resultat_intervenant fri JOIN intervenant i ON i.id = fri.intervenant_id AND fri.etat_volume_horaire_id = 1";
    $bdd->exec($sql);
}

$fs      = $container->get(FormuleService::class);
$ts      = $container->get(TestService::class);
$testeur = new Testeur();

$sql   = "SELECT * FROM test_arr WHERE annee_id = 2024 AND passe = 0";
$data  = $bdd->select($sql, [], ['case' => CASE_LOWER]);
$nb    = 0;
$count = count($data);
foreach ($data as $d) {
    $nb++;
    echo "Item $nb / $count ...\n";

    $intervenantId       = (int)$d['intervenant_id'];
    $typeVolumeHoraireId = (int)$d['type_volume_horaire_id'];
    $etatVolumeHoraireId = (int)$d['etat_volume_horaire_id'];

    $fi = $fs->getFormuleServiceIntervenant($intervenantId, $typeVolumeHoraireId, $etatVolumeHoraireId);

    $resKey = [
        'INTERVENANT_ID'         => $intervenantId,
        'TYPE_VOLUME_HORAIRE_ID' => $typeVolumeHoraireId,
        'ETAT_VOLUME_HORAIRE_ID' => $etatVolumeHoraireId,
    ];

    $resData = [
        'PASSE'           => 1,
        'ERR_NONE'        => 0,
        'ERR_MINIMAL'     => 0,
        'ERR_FULL'        => 0,
        'ERR_CUSTOM_MIN'  => 0,
        'ERR_CUSTOM_FULL' => 0,
    ];

    $fi->setArrondisseur(FormuleIntervenant::ARRONDISSEUR_NO);
    $ts->getServiceFormule()->calculer($fi);
    $resData['ERR_NONE'] = $testeur->tester($fi);

    $fi->setArrondisseur(FormuleIntervenant::ARRONDISSEUR_MINIMAL);
    $ts->getServiceFormule()->calculer($fi);
    $resData['ERR_MINIMAL'] = $testeur->tester($fi);

    $fi->setArrondisseur(FormuleIntervenant::ARRONDISSEUR_FULL);
    $ts->getServiceFormule()->calculer($fi);
    $resData['ERR_FULL'] = $testeur->tester($fi);

    $fi->setArrondisseur(FormuleIntervenant::ARRONDISSEUR_CUSTOM);
    $ts->getServiceFormule()->calculer($fi);
    $resData['ERR_CUSTOM_FULL'] = $testeur->tester($fi);

    $bdd->getTable($testTablename)->update($resData, $resKey);
}

