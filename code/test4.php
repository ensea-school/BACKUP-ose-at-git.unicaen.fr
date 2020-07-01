<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/* @var $intervenant \Application\Entity\Db\Intervenant */
$intervenant = $container->get(\Application\Service\IntervenantService::class)->get(578);

$ni = $intervenant->dupliquer();