<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Intervenant\Service\IntervenantService $fi */
$fi = $container->get(\Intervenant\Service\IntervenantService::class);

/** @var \Formule\Service\FormuleService $fs */
$fs = $container->get(\Formule\Service\FormuleService::class);

/** @var \Formule\Service\FormulatorService $formulator */
$formulator = $container->get(\Formule\Service\FormulatorService::class);

$typeVolumeHoraire = $container->get(\Service\Service\TypeVolumeHoraireService::class)->getRealise();
$etatVolumeHoraire = $container->get(\Service\Service\EtatVolumeHoraireService::class)->getValide();


$intervenant = $fi->get(784094);


$fs->calculer($intervenant, $typeVolumeHoraire, $etatVolumeHoraire);
