<?php

namespace Administration\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of ClearCacheCommand
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class ClearCacheCommand extends Command
{
    use EntityManagerAwareTrait;

    protected function configure(): void
    {
        $this->setDescription('Nettoyage des caches et mise à jour des proxies Doctrine');
    }



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $em = $this->getEntityManager();

        $filesystem = new Filesystem();
        try {
            // Suppression des fichiers de cache
            $cachePath = getcwd() . '/cache';
            if ($filesystem->exists($cachePath)) {
                $filesystem->remove($cachePath);
            }

            // Nettoyage des proxies
            $destPath = $em->getConfiguration()->getProxyDir();
            if (!is_dir($destPath)) {
                mkdir($destPath, 0775, true);
            }

            $destPath = realpath($destPath);
            $metadatas = $em->getMetadataFactory()->getAllMetadata();
            $em->getProxyFactory()->generateProxyClasses($metadatas, $destPath);

            // Réattribuer les permissions
            $filesystem->chmod($cachePath, 0777, 0000, true);

            $io->success('Cache nettoyé, proxies actualisés');
        } catch (\Exception $e) {
            $io->error($e->getMessage());
            $io->error(
                'Un problème est survenu : le cache de OSE n\'a pas été vidé. '
                . 'Merci de supprimer le contenu du répertoire /cache de OSE, puis de lancer la commande ./bin/ose clear-cache pour y remédier'
            );
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}