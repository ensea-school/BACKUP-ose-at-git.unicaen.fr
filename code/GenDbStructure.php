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

echo "<h1>Atention à bien se mettre sur OSE TEST BIEN à JOUR pour pouvoir générer les données!!!</h1>";

$em = $sl->get(Constants::BDD);

$dg = new \GenDbStructure\DdlGen($em);

$ddl = '';
$ddl = $dg->getDdl();

$de = new \GenDbStructure\DataGen($em);
$data = $de->getDdlData();

if (!isset($filename)) {
    $filename = false;
}

$sCodeGenerator->generateToFile($filename ?: 'ose-ddl.sql', $ddl.$data, (bool)$filename);
