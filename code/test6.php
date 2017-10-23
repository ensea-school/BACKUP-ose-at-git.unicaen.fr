<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\Context;

/** @var Context $sc */
$sc = $sl->get('applicationContext');


$role = $sc->getSelectedIdentityRole();

var_dump($role);