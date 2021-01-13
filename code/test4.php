<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


$qg = $container->get(\UnicaenImport\Service\QueryGeneratorService::class);

sqlDump($qg->makeDiffView('INTERVENANT'));