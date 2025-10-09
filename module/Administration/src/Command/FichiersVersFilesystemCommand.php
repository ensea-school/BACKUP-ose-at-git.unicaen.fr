<?php

namespace Administration\Command;

use Unicaen\Framework\Application\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of FichiersVersFilesystemCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class FichiersVersFilesystemCommand extends Command
{
    use BddAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Migration des fichiers stockées en base de données dans un filer');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io  = new SymfonyStyle($input, $output);
        $filesystem = new Filesystem();
        $io->title($this->getDescription());
        $config = Application::getInstance()->config();
        if ($config['fichiers']['stockage'] != 'file') {
            $io->error('Votre instance ne stocke pas les fichiers dans le système de fichiers');
            return Command::FAILURE;
        }
        $dir = $config['fichiers']['dir'];
        if (!str_ends_with($dir, '/')) $dir .= '/';

        $files = $this->bdd->select('SELECT ID, CONTENU FROM FICHIER WHERE CONTENU IS NOT NULL', [], ['fetch' => $this->bdd::FETCH_EACH]);
        $count = (int)$this->bdd->select('SELECT COUNT(*) c FROM FICHIER WHERE CONTENU IS NOT NULL', [], ['fetch' => $this->bdd::FETCH_ONE])['C'];
        $io->info("Transfert du contenu des $count fichiers de la base de données vers le système de fichiers");
        $io->progressStart($count);
        while ($fichier = $files->next()) {
            $io->progressAdvance();
            $id      = (int)$fichier['ID'];
            $contenu = $fichier['CONTENU'];

            $filename = 'd' . (str_pad((string)floor($id / 1000), 4, '0', STR_PAD_LEFT))
                . '/f'
                . str_pad((string)($id % 1000), 3, '0', STR_PAD_LEFT);

            $filename = $dir . $filename;
            if ($contenu && !$filesystem->exists($filename)) {
                if (!$filesystem->exists(dirname($filename))) {
                    $filesystem->mkdir(dirname($filename));
                }
                $filesystem->appendToFile($filename, $contenu);
                file_put_contents($filename, $contenu);
                if (file_exists($filename)) {
                    $this->bdd->getTable('FICHIER')->update(['CONTENU' => null], ['ID' => $id]);
                }
            }
        }
        $io->progressFinish();
        $io->success("Fin du transfert des fichiers de la base de données vers le filer");



        return Command::SUCCESS;
    }
}