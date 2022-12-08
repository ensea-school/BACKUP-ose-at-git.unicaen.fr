<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(\Application\Constants::BDD);

$e = $em->find(\Mission\Entity\Db\TypeMission::class, 1);

var_dump($e);