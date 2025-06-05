<?php

namespace Administration\Command;

use Administration\Service\AdministrationServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenCode\Util;

/**
 * Description of UpdateBddPrivilegesCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddPrivilegesCommand extends Command
{
    use BddAwareTrait;
    use AdministrationServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Mise à jour des privilèges de l\'application');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $bdd = $this->getBdd()->setLogger($io);

        $io->title($this->getDescription());
        try {
            $bdd->data()->run('privileges');

            Util::codeGenerator()->generer('privileges');

            $this->getServiceAdministration()->clearCache();
            $io->success('Privilèges à jour');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}