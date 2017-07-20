<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

/** @var \Application\Service\Context $s */
$s = $sl->get('ApplicationContext');
$i = $s->findPersonnel();

var_dump($i);