<?php

namespace Formule\Command;

use Application\Provider\Tbl\TblProvider;
use Application\Service\Traits\AnneeServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenTbl\Event;
use UnicaenTbl\Service\TableauBordServiceAwareTrait;

/**
 * Description of CalculCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CalculCommand extends Command
{
    use AnneeServiceAwareTrait;
    use TableauBordServiceAwareTrait;

    protected ProgressBar $progresBar;

    protected function configure(): void
    {
        $this->setDescription('Recalcul de toutes les formules')
            ->addArgument('anneeId', InputArgument::OPTIONAL, 'Id de l\'année pour laquelle seront lancées les formules');;
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $io->title($this->getDescription());

        $anneeId = $input->getArgument('anneeId');

        $io->warning("Ce traitement peut prendre plusieurs minutes");

        $this->getServiceTableauBord()->setOnAction(function(Event $event) use ($io){
            $this->onEvent($event, $io);
        });

        $annees = $this->getServiceAnnee()->getActives(true);
        foreach ($annees as $annee) {
            if ($annee->getId() == $anneeId || $anneeId === null) {
                $io->comment('Calcul pour l\'année '.$annee->getLibelle());
                $params = ['ANNEE_ID' => $annee->getId()];
                $this->getServiceTableauBord()->calculer(TblProvider::FORMULE, $params);
            }
        }

        return Command::SUCCESS;
    }



    protected function onEvent(Event $event, SymfonyStyle $io)
    {
        switch ($event->action){
            case Event::CALCUL:
                break;
            case Event::FINISH:
                $io->info("Calcul effectué en ".round($event->tableauBord->getTiming(), 3)." secondes");
                break;
            case Event::GET:
                $io->info("Récupération des données");
                break;
            case Event::PROCESS:
                $io->info("Traitement");
                $this->progresBar = $io->createProgressBar($event->total);
                break;
            case Event::SET:
                $io->info("Enregistrement");
                $this->progresBar = $io->createProgressBar($event->total);
                break;
            case Event::PROGRESS:
                $this->progresBar->setMaxSteps($event->total);
                $this->progresBar->setProgress($event->progress);
        }
    }
}