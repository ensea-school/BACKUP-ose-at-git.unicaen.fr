<?php

namespace Formule\Command;

use Formule\Service\FormulatorServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

/**
 * Description of BuildCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class BuildCommand extends Command
{
    use FormulatorServiceAwareTrait;

    protected function configure(): void
    {
        $this->addArgument('name');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $formuleName = $input->getArgument('name') ?? '';

        $dir      = getcwd() . '/data/formules';
        $fichiers = scandir($dir);

        $cacheDir = $this->getServiceFormulator()->cacheDir();

        if (file_exists($cacheDir) && !$formuleName) {

            $process = new Process(['rm','-Rf',$cacheDir]);
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            echo $process->getOutput();
        }

        $io->title('Construction de toutes les formules de calcul');

        foreach ($fichiers as $fichier) {
            if (!str_starts_with($fichier, '.') && (strtolower($fichier) == strtolower($formuleName) . '.ods' || empty($formuleName))) {
                $io->writeln('Construction de ' . $fichier . ' ...');
                try {
                    $filename = $dir . '/' . $fichier;
                    $this->getServiceFormulator()->implanter($filename);
                } catch (\Exception $e) {
                    $io->error($e->getMessage() . "\n" . $e->getFile() . ' ligne ' . $e->getLine());
                }
            }
        }

        $io->comment('Formules construites');

        $io->info('Répertoire des formules : '.$dir);

        return Command::SUCCESS;
    }
}