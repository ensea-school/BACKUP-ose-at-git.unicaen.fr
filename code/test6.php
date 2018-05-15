<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Constants;

$sql = file_get_contents('data/Query/plafond.sql');
$sql = str_replace('/*i.id*/', 'AND i.id = ' . 51647, $sql) . ' AND tvh.id = ' . 1;

$sql =  preg_replace('/--(.*)\n/Uis', "\n", $sql) ;
sqlDump($sql);
$res          = $sl->get(Constants::BDD)->getConnection()->fetchAll($sql);

var_dump($res);