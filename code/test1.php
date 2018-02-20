<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\StructureService;


/** @var StructureService $ss */
$ss = $sl->get(StructureService::class);

$qb = $ss->finderByEnseignement();
$s = $ss->getList($qb);

echo $qb->getQuery()->getSQL();

var_dump($s);