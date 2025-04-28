<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 */

use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenTbl\Event;

$tbl = $container->get(\UnicaenTbl\Service\TableauBordService::class);

$params = [
//    'INTERVENANT_ID' => 36215,
//    'INTERVENANT_ID' => 777477,
    'ANNEE_ID' => 2020,
];

$tbl->calculer('contrat', $params);
