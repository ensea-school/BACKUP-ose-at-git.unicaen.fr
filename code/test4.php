<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Plafond\Service\PlafondService $sp */
$sp = $container->get(\Plafond\Service\PlafondService::class);

$intervenantId = 58753;
$tvhId         = 1;

$intervenant = $container->get(\Application\Service\IntervenantService::class)->get($intervenantId);
$tvh         = $container->get(\Application\Service\TypeVolumeHoraireService::class)->get($tvhId);

$sp->controle($intervenant, $tvh);