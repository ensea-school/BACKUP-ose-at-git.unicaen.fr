<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

$si = $container->get(\Application\Service\IntervenantService::class);
//$i = $si->get(193437);


/** @var \ExportRh\Service\ExportRhService $erhs */
$erhs = $container->get(\ExportRh\Service\ExportRhService::class);


$p = $erhs->getIntervenantExportParams();
var_dump($p);

//$p->prenom = true;

$erhs->saveIntervenantExportParams();

//$sc = $container->get(\ExportRh\Connecteur\Siham\SihamConnecteur::class);
//$sc->test();