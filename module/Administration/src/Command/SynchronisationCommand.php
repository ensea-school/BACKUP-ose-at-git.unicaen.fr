<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use UnicaenImport\Processus\Traits\ImportProcessusAwareTrait;

/**
 * Description of SynchronisationCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class SynchronisationCommand extends Command
{
    use ImportProcessusAwareTrait;

    protected function configure(): void
    {
        $this->setName('calcul-feuille-de-route')
            ->setDescription('Lance les jobs de synchronisation de OSE, non du job en argument')
            ->addArgument('job', InputArgument::REQUIRED, 'Nom du job que vous souhaitez lancer');

    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $oa = \OseAdmin::instance();

        $io->title('Lancement des jobs de synchronisation de OSE');
        if ($oa->inMaintenance()) {
            $io->writeln("OSE est en maintenance. La synchronisation est coupée pendant ce temps");
            return Command::FAILURE;
        } elseif ($oa->config()->get('maintenance', 'desactivationSynchronisation', false)) {
            $io->writeln("La synchronisation est désactivée");
            return Command::FAILURE;
        } else {
            $job = $input->getArgument('job');
            if (empty($job)) {
                $io->error("Vous devez préciser le job à lancer");
                return Command::FAILURE;

            }
            $io->writeln("Lancement du job '" . $job . "'");
            $this->getProcessusImport()->syncJob($job);
            $io->writeln("Fin du job '" . $job . "'");
        }

        return Command::SUCCESS;
    }

}