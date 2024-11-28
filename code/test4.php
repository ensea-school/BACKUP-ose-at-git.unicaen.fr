<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$sf = $container->get(\Formule\Service\FormuleService::class);

$params = [
    'INTERVENANT_ID' => 381
];

$sTbl = $sf->getServiceTableauBord();
$sTbl->calculer('formule', $params);