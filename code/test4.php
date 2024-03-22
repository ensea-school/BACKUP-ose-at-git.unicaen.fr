<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Formule\Service\TestService $ts */
$ts = $container->get(\Formule\Service\TestService::class);

/** @var \Formule\Service\FormulatorService $fs */
$fs = $container->get(\Formule\Service\FormulatorService::class);

/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(\Doctrine\ORM\EntityManager::class);

$fi = $ts->get(10013);
$formule = $em->find(\Formule\Entity\Db\Formule::class, 12);

$fs->calculer($fi, $formule);

var_dump($fi->getHeuresServiceFi());
var_dump($fi->getHeuresComplFi());