<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Doctrine\ORM\EntityManager $em */
$em = $container->get(\Doctrine\ORM\EntityManager::class);

/** @var \Paiement\Service\BudgetService $bs */
$bs = $container->get(\Paiement\Service\BudgetService::class);

$structure = $em->getRepository(\Lieu\Entity\Db\Structure::class)->find(372);

$res = $bs->getTotalPrevisionnelValide($structure);

var_dump($res);