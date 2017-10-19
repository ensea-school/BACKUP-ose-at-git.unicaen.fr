<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $viewName   string
 * @var $sl         \Zend\ServiceManager\ServiceLocatorInterface
 */

//throw new \Exception('test');
use Application\Entity\Db\Intervenant;
use Application\Service\WorkflowService;

/** @var WorkflowService $w */
$w = $sl->get('workflow');

$i = new Intervenant();

//$w->calculerTableauxBord(['workflow','formule'], $i);



