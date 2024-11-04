<?php

namespace Indicateur\Command;

use Indicateur\Processus\IndicateurProcessusAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\Console\Console;

/**
 * Description of NotifierCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class NotifierCommand extends SymfonyCommand
{
    use IndicateurProcessusAwareTrait;

    private SymfonyStyle $io;



    protected function configure(): void
    {
        $this
            ->setDescription('Lance les notifications des indicateurs')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Forcer l\'envoi de toutes les notifications');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $force = $input->getOption('force');
        $this->getProcessusIndicateur()->envoiNotifications($force);
        $this->io->info('Les notifications ont été envoyées avec succès');

        return Command::SUCCESS;


    }
}