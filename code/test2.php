<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Application\Service\FichierService $fs */
$fs = $container->get(\Application\Service\FichierService::class);

$fichier = $fs->get(87124);
//var_dump($fichier);
$fs->save($fichier);

//var_dump(stream_get_contents($fichier->getContenu()));
//var_dump($fs->getFichierFilename($fichier));
