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
$s = $em->find(\Intervenant\Entity\Db\Statut::class, 2799);
//$s->setServiceStatutaire($s->getServiceStatutaire() + 1);
//$em->persist($s);
//$em->flush($s);

/*
$ps = new \Plafond\Entity\Db\PlafondStatut();
$ps->setPlafond($em->find(\Plafond\Entity\Db\Plafond::class, 2));
$ps->setStatut($s);
$ps->setEtatRealise($em->find(\Plafond\Entity\Db\PlafondEtat::class, 1));
$ps->setEtatPrevu($em->find(\Plafond\Entity\Db\PlafondEtat::class, 2));
$ps->setHeures(1);
*/

/** @var \Plafond\Entity\Db\PlafondStatut $ps */
$ps = $em->find(\Plafond\Entity\Db\PlafondStatut::class, 952);
$ps->setHeures($ps->getHeures() + 1);
$em->persist($ps);
$em->flush($ps);
