<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$sp = $sl->get(\Application\Service\PaysService::class);


$france = $sp->getIdByLibelle('Algérie');

var_dump($france);