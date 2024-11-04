<?php

namespace Administration\Command;

use Administration\Service\AdministrationServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of ClearCacheCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ClearCacheCommand extends Command
{
    use AdministrationServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Nettoyage des caches et mise à jour des proxies Doctrine');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->getServiceAdministration()->clearCache();

            $io->success('Cache nettoyé, proxies actualisés');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            $io->error(
                'Un problème est survenu : le cache de OSE n\'a pas été vidé. '
                . 'Merci de supprimer le contenu du répertoire /cache de OSE, puis de lancer la commande ./bin/ose clear-cache pour y remédier'
            );
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}