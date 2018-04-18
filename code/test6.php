<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use UnicaenImport\Service\QueryGeneratorService;


/** @var QueryGeneratorService $qg */
$qg = $sl->get(QueryGeneratorService::class);

$sm = $qg->getEntityManager()->getConnection()->getSchemaManager();


//$r = $qg->makeDiffView('VOLUME_HORAIRE_ENS');

var_dump($sm->listViews());