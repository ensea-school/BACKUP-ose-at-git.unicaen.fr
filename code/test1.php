<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

$sp = $sl->get(\Application\Service\FonctionReferentielService::class);


$f = $sp->get(324);

var_dump($f->getFille()->count());