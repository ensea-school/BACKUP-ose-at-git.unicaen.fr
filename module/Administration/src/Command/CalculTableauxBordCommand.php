<?php

namespace Administration\Command;

use Application\Provider\Tbl\TblProvider;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenTbl\Event;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;

/**
 * Description of CalculTableauxBordCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculTableauxBordCommand extends Command
{
    use TableauBordServiceAwareTrait;

    private ProgressBar $progressBar;



    protected function configure(): void
    {
        $this->setDescription('Calcul des tableaux de bord')
            ->addArgument('tableau-bord', InputArgument::OPTIONAL, 'Tableau de bord à calculer (si pas précisé, tous se recalculeront')
            ->addOption('annee', 'a', InputOption::VALUE_OPTIONAL, 'ID Année')
            ->addOption('intervenant', 'i', InputOption::VALUE_OPTIONAL, 'ID Intervenant');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title($this->getDescription());

        $tableauBord = $input->getArgument('tableau-bord');
        $anneeId = $input->getOption('annee');
        $intervenantId = $input->getOption('intervenant');

        $this->progressBar = $io->createProgressBar(0);

        $this->getServiceTableauBord()->setOnAction(function (Event $event) use ($io) {
            $this->onEvent($event, $io);
        });

        if ($tableauBord) {
            $params = [];
            if ($anneeId) {
                $params['ANNEE_ID'] = (int)$anneeId;
            }
            if ($intervenantId){
                $params['INTERVENANT_ID'] = (int)$intervenantId;
            }
            $this->getServiceTableauBord()->calculer($tableauBord, $params);

            return Command::SUCCESS;
        }else{
            $result = $this->getServiceTableauBord()->calculerTout([TblProvider::FORMULE]);

            $io->comment('Fin du calcul des tableaux de bord');
            if ($result) {
                $io->success('Tout c\'est bien passé');
                return Command::SUCCESS;
            } else {
                $io->error('Attention : des erreurs ont été rencontrées!!');
                return Command::FAILURE;
            }
        }
    }



    protected function onEvent(Event $event, SymfonyStyle $io)
    {
        switch ($event->action) {
            case Event::CALCUL:
                $io->block("Calcul de " . $event->tableauBord->getName());
                break;
            case Event::FINISH:
                $io->info("Calcul effectué en " . round($event->tableauBord->getTiming(), 3) . " secondes");
                break;
            case Event::GET:
                $io->info("Récupération des données");
                break;
            case Event::PROCESS:
                $io->info("Traitement");
                $this->progressBar = $io->createProgressBar($event->total);
                break;
            case Event::SET:
                $io->info("Enregistrement");
                $this->progressBar = $io->createProgressBar($event->total);
                break;
            case Event::PROGRESS:
                $this->progressBar->setMaxSteps($event->total);
                $this->progressBar->setProgress($event->progress);
        }
    }

}