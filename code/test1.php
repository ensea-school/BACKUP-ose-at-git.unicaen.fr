<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use UnicaenCode\Util;

$file = getcwd() . '/cache/OSE-calculHC-Rennes2-20211101.xlsx';

$fc = new \Application\Model\FormuleCalcul($file, 'TEST');


$f = 'IF(ISERROR([.J20]);1;[.J20])*IF(AND([.$A20]="ES3";i_structure_code<>"ES3";[.$AE$15]>=12);4/3;1)';

$f = 'SUM([.AD$1:.AD$1048576])';

$f = 'IF([.AJ$15]>0;[.AI20]/[.AJ$15];0)';

//$f = 'IF(AND([.$D20]="Oui";[.$N20]<>"Oui";[.$A20]=i_structure_code;[.$O20]="Oui");IF([.$AG$16];[.$M20]*[.$AD20];0);0)';


//xmlDump($fc->getSheet()->getNode());

//$fc->testFormule($f);

$c = $fc->getSheet()->getCell('AG16');
var_dump($c->getDeps());

//echo $fc->getSheet(1)->html();

//Util::highlight($fc->makePackageBody(), 'plsql', true, ['show-line-numbers' => true]);


//xmlDump($fc->getSheet()->getNode());

