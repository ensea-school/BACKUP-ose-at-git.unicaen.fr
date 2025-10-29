<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Unicaen\BddAdmin\Bdd;

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

        $io->section("Mise en place des répertoires de travail");

        $dirs = [
            'var',
            'var/cache',
            'var/session',
            'var/log',

            'data/fichiers',
            'data/signature',
        ];

        foreach( $dirs as $dir ) {
            if (!$filesystem->exists($dir)) {
                $filesystem->mkdir($dir);
            }
            $filesystem->chmod($dir, 0777);
        }

        $filesystem->chmod('bin/ose', 0755);
        $filesystem->chmod('bin/ose-code', 0755);
        $filesystem->chmod('bin/ose-test', 0755);

        $io->comment('Initialisation des répertoires de travail OK');

        if ($this->hasConfigBdd()) {
            $this->runCommand($output, 'install-bdd', ['--oseappli-pwd' => 'no']);
        } else {
            $io->section("Il reste encore plusieurs étapes à réaliser pour que OSE soit pleinement fonctionnel :");

            $io->listing([
                             "1 - Configurez le cas échéant votre serveur Apache",
                             "2 - Veuillez personnaliser le fichier de configuration de OSE `config.local.php`",
                             "3 - La base de données devra être initialisée à l'aide de la commande `./bin/ose install-bdd`",
                             "4 - Mettez en place les tâches CRON nécessaires (envoi de mails pour les indicateurs, Synchronisation automatique, etc.)",
                         ]);

            $io->text(
                "Pour la suite, merci de vous reporter au guide de l'administrateur pour vous aider à configurer l'application",
            );
        }

        return Command::SUCCESS;
    }



    protected function hasConfigBdd(): bool
    {
        $config = require 'config.local.php';
        $config = $config['bdd'];
        try {
            new Bdd($config);
            return true;
        }catch (\Exception $e){
            return false;
        }
    }



    private function runCommand(OutputInterface $output, string $commandName, array $options = []): int
    {
        $command = $this->getApplication()->get($commandName);
        $input   = new ArrayInput($options);

        return $command->run($input, $output);
    }
}