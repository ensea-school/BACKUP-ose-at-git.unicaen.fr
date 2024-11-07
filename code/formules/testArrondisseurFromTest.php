<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

/** @var \Formule\Service\TestService $ts */
$ts = $container->get(\Formule\Service\TestService::class);

$intervenantTestId = 211734;
$fi                = $ts->get($intervenantTestId);
$arrondir          = false;

$ts->getServiceFormule()->calculer($fi, $arrondir);
$aff = new \Formule\Model\Arrondisseur\Afficheur();
$aff->afficher($fi->getArrondisseurTrace());