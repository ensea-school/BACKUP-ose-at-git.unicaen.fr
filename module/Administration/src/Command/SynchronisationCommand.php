<?php

namespace Administration\Command;

use Application\Provider\Role\RoleProvider;
use Application\Service\Traits\AffectationServiceAwareTrait;
use Doctrine\Common\Cache\FilesystemCache;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
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
    use AffectationServiceAwareTrait;

    protected function configure(): void
    {
        $this->setName('synchronisation')
            ->setDescription('Lancement les jobs de synchronisation de OSE')
            ->addArgument('job', InputArgument::REQUIRED, 'Nom du job que vous souhaitez lancer');

    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title($this->getDescription());
        if (\AppAdmin::inMaintenance()) {
            $io->writeln("OSE est en maintenance. La synchronisation est coupée pendant ce temps");
            return Command::FAILURE;
        } elseif (\AppAdmin::config()['maintenance']['desactivationSynchronisation'] ?? false) {
            $io->writeln("La synchronisation est désactivée");
            return Command::FAILURE;
        } else {
            $job = $input->getArgument('job');
            if (empty($job)) {
                $io->error("Vous devez préciser le job à lancer");
                return Command::FAILURE;

            }
            $io->writeln("Lancement du job '" . $job . "'");
            //$this->getProcessusImport()->syncJob($job);
            $io->writeln("Fin du job '" . $job . "'");
            //Suppresion du cache des affectations
            $io->writeln("Suppression du cache doctrine des affectations");
            $this->getServiceAffectation()->deleteCacheAffectation();
        }

        return Command::SUCCESS;
    }

    /**
     * @param FilesystemCache $doctrineCache
     */
    public function setDoctrineCache(FilesystemCache $doctrineCache)
    {
        $this->doctrineCache = $doctrineCache;

        return $this;
    }

}