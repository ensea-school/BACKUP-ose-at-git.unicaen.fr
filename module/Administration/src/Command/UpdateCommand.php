<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
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
                'maintenance',
                'm',
                InputOption::VALUE_OPTIONAL,
                'Indique si un message de maintenance doit être affiché (y ou n)',
                'yes' // Valeur par défaut
            )
            ->addOption(
                'cible',
                'c',
                InputOption::VALUE_OPTIONAL,
                'Version cible de OSE à déployer',
            );
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title($this->getDescription());

        $maintenanceMsg = $input->getOption('maintenance');

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
        $version = $input->getOption('cible');

        if (!empty($version)){
            $args = "--cible=$version";
        }else{
            $args = "";
        }

        $bin = getcwd()."/bin/ose";

        passthru("$bin update-code $args");
        passthru("$bin update-bdd");

        //Conclusion
        $io->success("Fin de la mise à jour.");
        if ($maintenanceMsg === 'yes') {
            $io->warning("N'oubliez pas de sortir du mode maintenance!");
        }

        return Command::SUCCESS;
    }

}