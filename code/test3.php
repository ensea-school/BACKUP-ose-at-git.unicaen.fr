<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Mission\Service\MissionService $sMission */
$sMission = $container->get(\Mission\Service\MissionService::class);

$mission = $sMission->get(31);

$mission->setHeures(10);

$sMission->save($mission);