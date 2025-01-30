<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);

/** @var \Formule\Service\TestService $ts */
$ts = $container->get(\Formule\Service\TestService::class);


$intervenantId = 777000;
//$intervenantId = 2032;
$typeVolumeHoraireId = 1;
$etatVolumeHoraireId = 1;
$arrondir = \Formule\Entity\FormuleIntervenant::ARRONDISSEUR_FULL;

$fi = $fs->getFormuleServiceIntervenant($intervenantId, $typeVolumeHoraireId, $etatVolumeHoraireId);
$fi->setArrondisseur($arrondir);

/* Calcul & affichage des résultats d'arrondissage */
if (isset($fi)) {
    $ts->getServiceFormule()->calculer($fi);
    $aff = new \Formule\Model\Arrondisseur\Afficheur();
    $aff->afficher($fi->getArrondisseurTrace());
}