<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

use Application\Service\WorkflowService;



/** @var WorkflowService $s */
$s = $sl->get(WorkflowService::class);

$s->calculerTousTableauxBord();