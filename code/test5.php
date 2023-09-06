<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $controller \Laminas\Mvc\Controller\AbstractController
 * @var $container  \Psr\Container\ContainerInterface
 * @var $viewName   string
 * @var $viewFile   string
 */

/** @var \Paiement\Service\TauxRemuService $trs */
$trs = $container->get(\Paiement\Service\TauxRemuService::class);

$val = $trs->tauxValeur(4, new \DateTime());

var_dump($val);