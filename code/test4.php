<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Application\Provider\Chargens\ChargensProvider $cp */
$cp = \Application::$container->get(\Application\Provider\Chargens\ChargensProvider::class);


$filename  = getcwd() . '/data/charges.csv';
$filename2 = getcwd() . '/data/charges2.csv';

$avant = $cp->getExport()->fromCsv($filename);
$apres = $cp->getExport()->fromCsv($filename2);

$d = $cp->getExport()->diff($data, $data2);

var_dump($d);