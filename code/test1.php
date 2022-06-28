<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

use UnicaenCode\Util;

$fc = new \Application\Model\FormuleCalcul(getcwd() . '/cache/guyane.xlsx', 'GUYANE');


$f = 'IF(ISERROR([.J20]);1;[.J20])*IF(AND([.$A20]="ES3";i_structure_code<>"ES3";[.$AE$15]>=12);4/3;1)';

$f = 'SUM([.AD$1:.AD$1048576])';

return $fc->testFormule($f);

//echo $fc->getSheet(1)->html();

Util::highlight($fc->makePackageBody(), 'plsql', true, ['show-line-numbers' => true]);
