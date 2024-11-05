<?php

namespace Administration\Command;

use Dossier\Service\Traits\EmployeurServiceAwareTrait;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Unicaen\BddAdmin\BddAwareTrait;

/**
 * Description of UpdateEmployeur
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class UpdateEmployeur extends Command
{
    use BddAwareTrait;
    use EmployeurServiceAwareTrait;


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filesystem = new Filesystem();
        $oseSource  = \OseAdmin::instance()->getSourceOseId();
        $oseId      = \OseAdmin::instance()->getOseAppliId();
        $io         = new SymfonyStyle($input, $output);
        $io->title('Mise à jour de la table employeurs');
        $importDirectory = dirname(__DIR__, 4) . '/cache/employeurs/';
        $importArchive   = 'employeurs.tar.gz';
        $importFilePath  = $importDirectory . $importArchive;
        if ($filesystem->exists($importDirectory)) {
            $filesystem->mkdir($importDirectory);
        }
        $io->writeln("Récupération de l'archive employeur...");
        $processRecupTar = new Process(['wget', '-O', 'employeurs.tar.gz', 'https://ose.unicaen.fr/employeurs.tar.gz'], $importDirectory);

        $processRecupTar->run();
        if (!$processRecupTar->isSuccessful()) {
            $io->error("Impossible de récupérer l'archive employeur. Mise jour annulée.");
            return Command::FAILURE;
        }
        //On a bien récupérer l'archive
        if ($filesystem->exists($importFilePath)) {
            $phar = new \PharData($importFilePath);
            $phar->extractTo($importDirectory, [], true);
            $processUnTar = new Process(['tar', '-xvf', $importFilePath], $importDirectory);
            $processUnTar->run();
        }
        //On vérifie si la source INSEE existe dans OSE
        $io->writeln('Vérification que la source INSEE existe bien dans OSE');
        $haveAlreadyInseeSource = $this->bdd->select("SELECT * FROM source WHERE code='INSEE'", [], ['fetch' => $this->bdd::FETCH_ONE]);
        //Sinon on la crée
        if (!($haveAlreadyInseeSource)) {
            $io->writeln('La source INSEE n\'existe pas, on la crée');
            $data = ['CODE' => 'INSEE', 'LIBELLE' => 'INSEE', 'IMPORTABLE' => 1];
            $this->bdd->getTable('SOURCE')->insert($data);
            $idSource = $this->bdd->selectOne('select ID from source where code = :code', ['CODE' => 'INSEE'], 'ID');
            $this->bdd->exec("UPDATE employeur SET source_id = $idSource");
        } else {
            $idSource = $haveAlreadyInseeSource['ID'];
            $io->writeln("La source INSEE existe déjà avec l'ID : " . $idSource);
        }
        //On récupére les fichiers csv à importer
        $listFiles = preg_grep('~\.(csv)$~', scandir($importDirectory));
        $nbFiles   = count($listFiles);
        $io->writeln("Nombre de fichier à charger : $nbFiles");
        foreach ($listFiles as $file) {
            $filepath = $importDirectory . $file;
            $this->getServiceEmployeur()->mergeDatasEmployeur($filepath, $idSource, $oseId);
            break;
        }
        

        return Command::SUCCESS;


    }
}