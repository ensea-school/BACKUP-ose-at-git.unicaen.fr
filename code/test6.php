<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use UnicaenImport\Processus\ImportProcessus;


/** @var ImportProcessus $ip */
$ip = $sl->get(ImportProcessus::class);



$ip->syncJob('test1');