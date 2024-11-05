<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Description of InstallCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class InstallCommand extends Command
{
    protected function configure(): void
    {
        $this->setDescription('Installation de l\'application');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io         = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();

        $io->title($this->getDescription());

        if (!$filesystem->exists('cache')) {
            $filesystem->mkdir('cache');
        }
        $filesystem->chmod('cache', 0777);

        if (!$filesystem->exists('data/fichiers')) {
            $filesystem->mkdir('data/fichiers');
        }
        $filesystem->chmod('data/fichiers', 0777);

        if (!$filesystem->exists('log')) {
            $filesystem->mkdir('log');
        }
        $filesystem->chmod('cache', 0777);

        $filesystem->chmod('bin/ose', 0755);

        if (!$filesystem->exists('config.local.php')) {
            $filesystem->copy('config.local.php.default', 'config.local.php');
        }

        $io->comment('Initialisation des répertoires de travail OK');

        $io->section("Il reste encore plusieurs étapes à réaliser pour que OSE soit pleinement fonctionnel :");

        $io->listing([
                         "1 - Configurez le cas échéant votre serveur Apache",
                         "2 - Veuillez personnaliser le fichier de configuration de OSE `config.local.php`, si ce n'est déjà le cas",
                         "3 - La base de données devra au besoin être initialisée à l'aide de la commande `./bin/ose install-bdd`",
                         "4 - Mettez en place les tâches CRON nécessaires (envoi de mails pour les indicateurs, Synchronisation automatique, etc.)",
                     ]);

        $io->text(
            "Pour la suite, merci de vous reporter au guide de l'administrateur pour vous aider à configurer l'application",
        );

        return Command::SUCCESS;
    }
}