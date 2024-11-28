<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);

/** @var \Formule\Service\TestService $ts */
$ts = $container->get(\Formule\Service\TestService::class);


$intervenantId = 784094;
$intervenantId = 2032;
$typeVolumeHoraireId = 1;
$etatVolumeHoraireId = 1;
$arrondir = false;

$fi = $fs->getFormuleServiceIntervenant($intervenantId, $typeVolumeHoraireId, $etatVolumeHoraireId);

/* Calcul & affichage des résultats d'arrondissage */
if (isset($fi)) {
    $ts->getServiceFormule()->calculer($fi, $arrondir);
    $aff = new \Formule\Model\Arrondisseur\Afficheur();
    $aff->afficher($fi->getArrondisseurTrace());
}