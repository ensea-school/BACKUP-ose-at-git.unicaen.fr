<?php

namespace Administration\Command;

use Administration\Service\AdministrationServiceAwareTrait;
use Lieu\Service\StructureServiceAwareTrait;
use Plafond\Service\PlafondServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of UpdateBddCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateBddCommand extends Command
{
    use BddAwareTrait;
    use AdministrationServiceAwareTrait;
    use StructureServiceAwareTrait;
    use PlafondServiceAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Mise à jour de la base de données');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $bdd = $this->getBdd()->setLogger($io);

        try {
            // Mise à jour générale de la BDD
            $bdd->update();

            // Traitements supplémentaires
            $this->getServiceStructure()->updateStructures();

            $io->title('Construction & calcul des plafonds');
            $this->getServicePlafond()->construire();

            $this->runCommand($output, 'calcul-tableaux-bord');
            $this->runCommand($output, 'clear-cache');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $io->error($e->getMessage());

            return Command::FAILURE;
        }
    }



    private function runCommand(OutputInterface $output, string $commandName, array $options = []): int
    {
        $command = $this->getApplication()->get($commandName);
        $input   = new ArrayInput($options);

        return $command->run($input, $output);
    }
}