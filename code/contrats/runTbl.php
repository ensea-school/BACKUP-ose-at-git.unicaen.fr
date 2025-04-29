<?php

/**
 * @var $this       \Application\View\Renderer\PhpRenderer
 * @var $container  \Psr\Container\ContainerInterface
 * @var $io         \Symfony\Component\Console\Style\SymfonyStyle
 * @var $input      \Symfony\Component\Console\Input\Input
 */

use UnicaenTbl\Event;

$tbl = $container->get(\UnicaenTbl\Service\TableauBordService::class);

$arguments = $input->getArguments()['arguments'];
unset($arguments[0]);

if (isset($arguments[1]) && $arguments[1] == 'help') {
    echo 'Arguments possibles'."\n";
    echo '- annee_id <integer> : Calcule le TBL Contrat pour une année donnée'."\n";
    echo '- statut_id <integer> : Calcule le TBL Contrat pour un statut donné'."\n";
    echo '- intervenant_id <integer> : Calcule le TBL Contrat pour un intervenant donné'."\n";
    echo 'Si pas d\'arguments, calcule l\'ensemble du TBL contrat sans filtre'."\n";
}

$params = [];
if (isset($arguments[1]) && isset($arguments[2])){
    $params[strtoupper($arguments[1])] = (int)$arguments[2];
}

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
