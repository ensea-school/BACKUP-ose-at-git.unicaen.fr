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
    public static bool $needCalculFormules = false;

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

            // On vide le cache
            $this->runCommand($output, 'clear-cache');

            // Traitements supplémentaires
            $this->getServiceStructure()->updateStructures();

            // on s'occupe des TBLs
            $io->title('Construction & calcul des plafonds');
            $this->getServicePlafond()->construire();

            // On reconstruit les formules
            $this->runCommand($output, 'build-formules');

            // On recalcule toutes les formules
            if (self::$needCalculFormules) {
                $this->runCommand($output, 'formule-calcul');
            }

            // Enfin on calcule les TBLs
            $this->runCommand($output, 'calcul-tableaux-bord');

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