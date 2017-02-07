<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */


/** @var \Application\Processus\IndicateurProcessus $p */
$p = $sl->get('processusIndicateur');

var_dump($p->getServiceContext());