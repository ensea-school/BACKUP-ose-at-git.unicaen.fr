<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */


/** @var $em \Doctrine\ORM\EntityManager */
$em = $container->get(\Application\Constants::BDD);


/** @var \Intervenant\Entity\Db\Statut $s */
//$s = $em->find(\Intervenant\Entity\Db\Statut::class, 2799);

$s = $em->find(\Plafond\Entity\Db\PlafondStatut::class, 567);

$pel = new \Application\ORM\Event\Listeners\ParametreEntityListener();

$d = $pel->extract($s);

var_dump($d);