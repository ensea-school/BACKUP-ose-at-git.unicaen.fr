<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Application\Service\IntervenantService $si */
$si = $container->get(\Application\Service\IntervenantService::class);

$intervenant = $container->get(\Application\Service\IntervenantService::class)->get(195999);


$data = $si->isImportable($intervenant);
var_dump($data);