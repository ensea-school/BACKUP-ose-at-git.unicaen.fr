<?php

namespace Administration\Command;

use Administration\Service\GitRepoServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Description of UpdateCodeCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateCodeCommand extends Command
{
    use GitRepoServiceAwareTrait;

    private OutputInterface $output;



    protected function configure(): void
    {
        $this
            ->setDescription('Mise à jour du code source')
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
        $this->output = $output;

        $repo = $this->getServiceGitRepo();

        // Récupération de la version ou de la branche cible
        $this->exec('git fetch --all --tags --prune');

        $version = $input->getOption('cible');
        if (empty($version)) {
            $io->section("Sélection de la version à déployer");
            $io->writeln("La version actuellement installée est la " . $repo->oldVersion());
            $io->writeln("Voici la liste des versions de OSE disponibles:");
            $tags = array_keys($repo->getTags());
            $io->listing($tags);

            // Choix de la version
            $version = $io->ask("Veuillez choisir une version cible à déployer: ");
        }

        if (!($repo->tagIsValid($version) || $repo->brancheIsValid($version))) {
            $io->error("$version n'est pas dans la liste des versions disponibles.");
            return Command::FAILURE;
        }


        // Récupération des sources
        $io->section("Mise à jour des fichiers à partir de GIT");
        $this->exec("git reset --hard"); // purge des modifs locales
        $tbr = $repo->tagIsValid($version) ? 'tags/' : '';
        if ($version == $repo->getCurrentBranche()) {
            $updcmd = 'git pull';
        } else {
            $updcmd = "git checkout $tbr$version";
        }
        $this->exec($updcmd);

        $repo->writeVersion($version);
        $io->success("Mise à jour du code source OK : la version installée est désormais la " . $version);


        // Récupération des dépendances
        $io->section("Mise à jour des dépendances à l'aide de Composer");
        $env = ['COMPOSER_ALLOW_SUPERUSER' => '1'];

        if (file_exists('composer.phar')) {
            $composerCmd = 'php composer.phar';
            $this->exec($composerCmd . ' self-update', $env);
        } else {
            $composerCmd = 'composer';
        }

        $this->exec($composerCmd . ' install --optimize-autoloader', $env);

        return Command::SUCCESS;
    }



    private function exec(string $command, array $env = []): void
    {
        $process = new Process(explode(' ', $command));
        $process->setEnv($env);
        $process->setWorkingDirectory(getcwd());

        try {
            $process->run(function ($type, $buffer) {
                $this->output->write($buffer); // Affiche la sortie en temps réel
            });
        } catch (ProcessFailedException $exception) {
            throw new \RuntimeException('Erreur lors de l\'exécution de la commande : ' . $exception->getMessage());
        }
        
    }
}