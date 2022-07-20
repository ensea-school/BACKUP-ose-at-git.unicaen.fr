<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use UnicaenCode\Util;

$file = getcwd() . '/cache/t.xlsx';

$fc = new \Application\Model\FormuleCalcul($file, 'TEST');


$cell = 'AN20';

$formule = 'IF(AND([.$E20]="Oui";[.$I20]<>"Référentiel";[.$I20]<>"ETD";[.$G20]=1;OR([.$A20]="I2000";[.$A20]="I2300"));[.$N20]*[.$AE20];0)';


$fc->testFormule($cell, $formule);

//xmlDump($fc->getSheet()->getCell($cell)->getNode());


//echo $fc->getSheet(1)->html();
//Util::highlight($fc->makePackageBody(), 'plsql', true, ['show-line-numbers' => true]);



