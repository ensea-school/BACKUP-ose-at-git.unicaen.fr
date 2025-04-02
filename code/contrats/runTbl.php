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
    //'INTERVENANT_ID' => 940962,
    //'INTERVENANT_ID' => 665114,
    //'ANNEE_ID' => 2021,
];

$progresBar = $io->createProgressBar(0);

$tbl->setOnAction(function (Event $event) use ($io, $progresBar) {
    switch ($event->action) {
        case Event::CALCUL:
            break;
        case Event::FINISH:
            $io->info("Calcul effectué en " . round($event->tableauBord->getTiming(), 3) . " secondes");
            break;
        case Event::GET:
            $io->info("Récupération des données");
            break;
        case Event::PROCESS:
            $io->info("Traitement");
            $progresBar = $io->createProgressBar($event->total);
            break;
        case Event::SET:
            $io->info("Enregistrement");
            $progresBar = $io->createProgressBar($event->total);
            break;
        case Event::PROGRESS:
            $progresBar->setMaxSteps($event->total);
            $progresBar->setProgress($event->progress);
    }
});

$tbl->calculer('contrat', $params);
