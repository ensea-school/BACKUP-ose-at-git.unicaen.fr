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
$s = $em->find(\Intervenant\Entity\Db\Statut::class, 7);
//$s->setConseilAcademique(false);
//$em->persist($s);
//$em->flush($s);

/** @var \Plafond\Entity\Db\PlafondStatut $ps */
$ps = $em->find(\Plafond\Entity\Db\PlafondStatut::class, 1);
$ps->setHeures(1);
$em->persist($ps);
$em->flush($ps);


