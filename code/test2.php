<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$intervenant = $container->get(\Application\Service\IntervenantService::class)->get(164270);

$tvh = $container->get(\Application\Service\TypeVolumeHoraireService::class)->getPrevu();
$evh = $container->get(\Application\Service\EtatVolumeHoraireService::class)->getSaisi();

/* @var $ftis \Application\Service\FormuleTestIntervenantService */
$ftis = $container->get(\Application\Service\FormuleTestIntervenantService::class);


$ftis->creerDepuisIntervenant($intervenant, $tvh, $evh);