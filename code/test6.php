<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/* @var $a \Plafond\Service\PlafondStructureService::class */
$a = $container->get(\Plafond\Service\PlafondStructureService::class);

//$anneeId = 2016;
//$annee   = $a->getServiceContext()->getEntityManager()->find(\Application\Entity\Db\Annee::class, $anneeId);

$ps = $a->get(2);
$ps->setHeures(77);

$em = $a->getEntityManager();

$em->persist($ps);
$em->flush($ps);