<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Plafond\Service\PlafondService $sp */
$sp = $container->get(\Plafond\Service\PlafondService::class);


$sp->construireVues();