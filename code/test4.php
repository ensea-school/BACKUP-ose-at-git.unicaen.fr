<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */


/** @var \Application\Provider\Chargens\ChargensProvider $s */
$s = $sl->get('chargens');


$res = $s->getSeuils()->getSeuils();
var_dump($res);
