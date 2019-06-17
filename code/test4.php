<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

/** @var \Application\Service\UtilisateurService $us */
$us = $sl->get(\Application\Service\UtilisateurService::class);


//$u = $us->creerUtilisateur('Farguet', 'Georges', New DateTime(), 'farguet', 'sifar14', true);

var_dump($u);