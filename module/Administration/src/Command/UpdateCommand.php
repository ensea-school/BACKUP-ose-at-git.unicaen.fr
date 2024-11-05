<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Description of UpdateCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateCommand extends Command
{
    protected function configure(): void
    {
        $this
            ->setDescription('Mise à jour de l\'application')
            ->addOption(
                'maintenance-msg',
                null,
                InputOption::VALUE_OPTIONAL,
                'Indique si un message de maintenance doit être affiché (y ou n)',
                'yes' // Valeur par défaut
            );;
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title($this->getDescription());

        $maintenanceMsg = $input->getOption('maintenance-msg');

        if ($maintenanceMsg === 'yes') {
            // Affichage du message de confirmation
            $output->writeln("Assurez-vous bien d'avoir mis OSE en mode maintenance avant de démarrer");

            // Attente de l'appui sur Entrée
            $io->ask("(pressez Entrée pour continuer)...");
        } elseif ($maintenanceMsg !== 'no') {
            $io->error('Valeur de --maintenance-msg invalide. Utilisez "yes" ou "no".');
            return Command::INVALID;
        }

        // Mise à jour du code source
        $this->runCommand($output, 'update-code');

        // Mise à jour de la base de données à partir d'un nouveau processus
        $this->runCommand($output, 'update-bdd');

        //Conclusion
        $io->success("Fin de la mise à jour.");
        if ($maintenanceMsg === 'yes') {
            $io->warning("N'oubliez pas de sortir du mode maintenance!");
        }

        return Command::SUCCESS;
    }



    private function runCommand(OutputInterface $output, string $commandName, array $options = []): int
    {
        $command = $this->getApplication()->get($commandName);
        $input   = new ArrayInput($options);

        return $command->run($input, $output);
    }
}