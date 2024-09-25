<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);

/** @var \Formule\Service\TestService $ts */
$ts = $container->get(\Formule\Service\TestService::class);


/* Lancement du TBL */
$sTbl = $fs->getServiceTableauBord();
$params = [
    'INTERVENANT_ID' => 899413,
    'TYPE_VOLUME_HORAIRE_ID' => 1,
    'ETAT_VOLUME_HORAIRE_ID' => 1,
    //'STATUT_ID' => 744,
    'ANNEE_ID'  => 2023,
];
$sTbl->calculer('formule', $params);
/* FIN */



/* récup depuis les services *
//$intervenantId = 784094;
$intervenantId = 899413;

$typeVolumeHoraireId = 1;
$etatVolumeHoraireId = 1;
$fi = $fs->getFormuleServiceIntervenant($intervenantId, $typeVolumeHoraireId, $etatVolumeHoraireId);
/* FIN */



/* récup depuis les tests de formule *
$intervenantTestId = 174360;
$fi = $ts->get($intervenantTestId);
/* FIN */



/* Calcul & affichage des résultats d'arrondissage */
if (isset($fi)) {
    $ts->getServiceFormule()->calculer($fi);
    $aff = new \Formule\Model\Arrondisseur\Afficheur();
    $aff->afficher($fi->getArrondisseurTrace());
}
/* FIN */
