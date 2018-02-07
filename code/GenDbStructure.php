<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Constants;

include_once 'GenDbStructure/DdlGen.php';
include_once 'GenDbStructure/DataGen.php';

$sCodeGenerator = $sl->get('UnicaenCode\CodeGenerator');
/* @var $sCodeGenerator \UnicaenCode\Service\CodeGenerator */

$em = $sl->get(Constants::BDD);

$dg = new \GenDbStructure\DdlGen($em);

$ddl = '';
$ddl = $dg->getDdl();
echo "<pre>$ddl</pre>";
//$sCodeGenerator->generateToFile('ose-ddl.sql', $ddl);


$de = new \GenDbStructure\DataGen($em);
$data = $de->getDdlData();

echo "<pre>$data</pre>";

$sCodeGenerator->generateToFile('ose-ddl.sql', $ddl.$data);
