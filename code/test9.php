<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 */

$tbl = $container->get(\UnicaenTbl\Service\TableauBordService::class);

$params = [
    //'INTERVENANT_ID' => 940962,
    'ANNEE_ID' => 2021,
];

$tbl->calculer('contrat', $params);
