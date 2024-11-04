<?php

namespace Administration\Command;

use Administration\Service\AdministrationServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;
use Unicaen\BddAdmin\Data\DataManager;

/**
 * Description of UpdateBddDataCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddDataCommand extends Command
{
    use BddAwareTrait;
    use AdministrationServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Mise à jour du jeu de données');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $bdd = $this->getBdd()->setLogger($io);

        $io->title('Contrôle et mise à jour du jeu de données');
        try {
            $bdd->data()->run(DataManager::ACTION_UPDATE);
            $this->getServiceAdministration()->clearCache();
            $io->success('Données à jour');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}