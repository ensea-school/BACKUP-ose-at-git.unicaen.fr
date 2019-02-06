<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$d = "2015-04-30 15:11:48";

$r = \DateTime::createFromFormat('Y-m-d H:i:s', $d);

var_dump( $r);