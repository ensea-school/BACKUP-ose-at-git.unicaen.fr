<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Formule\Service\FormulatorService $formulator */
$formulator = $container->get(\Formule\Service\FormulatorService::class);
$filename = "/app/data/formules/FORMULE_PARIS8.ods";

$tableur = $formulator->charger($filename);
//echo \Unicaen\OpenDocument\Calc\Display::sheet($tableur->sheet());
$cell = $tableur->sheet()->getCell('BS15');
// var_dump($cell);

var_dump($cell->getDeps());