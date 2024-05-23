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
$filename = "/app/data/formules/FORMULE_UBO.ods";

$tableur = $formulator->charger($filename);
//echo \Unicaen\OpenDocument\Calc\Display::sheet($tableur->sheet());

//$d = $tableur->tableur()->getAliases();


$cell = $tableur->sheet()->getCell('AF20');
$d = $cell->getDeps();



var_dump($d);


