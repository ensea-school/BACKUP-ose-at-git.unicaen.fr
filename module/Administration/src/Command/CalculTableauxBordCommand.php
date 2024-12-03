<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
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

    protected function configure(): void
    {
        $this->setDescription('Calcul des tableaux de bord');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);

        $io->title($this->getDescription());

        $this->getServiceTableauBord()->setOnAction(function(Event $event) use ($io){
            $this->onEvent($event, $io);
        });

        $result = $this->getServiceTableauBord()->calculerTout(['formule']);

        $io->comment('Fin du calcul des tableaux de bord');
        if ($result) {
            $io->success('Tout c\'est bien passé');
            return Command::SUCCESS;
        } else {
            $io->error('Attention : des erreurs ont été rencontrées!!');
            return Command::FAILURE;
        }
    }



    protected function onEvent(Event $event, SymfonyStyle $io)
    {
        switch ($event->action){
            case Event::CALCUL:
                $io->block("Calcul de ".$event->tableauBord->getName());
                break;
            case Event::FINISH:
                $io->info("Calcul effectué en ".round($event->tableauBord->getTiming(), 3)." secondes");
                break;
            default:
                echo $event->action."\n";
        }
    }

}