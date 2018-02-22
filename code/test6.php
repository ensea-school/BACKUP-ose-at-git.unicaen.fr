<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\StatutIntervenantService;

$si = $sl->get(StatutIntervenantService::class);

$s = $si->get(19);


$ns = $s->dupliquer();

var_dump($ns);