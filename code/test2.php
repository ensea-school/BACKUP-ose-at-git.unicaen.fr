<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Zend\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/**
 * @var $si \Application\Service\IntervenantService
 */
$si = $container->get(\Application\Service\IntervenantService::class);

$routeParams = [
    'code:OSE5ea29a39d99a0',
    '51954',
    '51965',
    '39778',
    '45xc',
];

foreach ($routeParams as $routeParam) {
    $i = $si->getByRouteParam($routeParam);
    var_dump($routeParam . ' = ' . ($i ? $i->getId() . ':' : '- NULL -') . $i);
}

