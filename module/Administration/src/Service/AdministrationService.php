<?php

namespace Administration\Service;

use Symfony\Component\Filesystem\Filesystem;
use Unicaen\BddAdmin\BddAwareTrait;
use UnicaenApp\Service\EntityManagerAwareTrait;

/**
 * Description of AdministrationService
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationService
{
    use EntityManagerAwareTrait;
    use BddAwareTrait;

    public function clearCache(): void
    {
        $em = $this->getEntityManager();

        $filesystem = new Filesystem();

        // Suppression des fichiers de cache
        $cachePath = getcwd() . '/cache';
        if ($filesystem->exists($cachePath)) {
            $content = scandir($cachePath);
            foreach( $content as $toRemove) {
                if ($toRemove != '.' && $toRemove != '..' ) {
                    $filesystem->remove($cachePath . '/' . $toRemove);
                }
            }
        }

        // Nettoyage des proxies
        $destPath = $em->getConfiguration()->getProxyDir();
        if (!is_dir($destPath)) {
            mkdir($destPath, 0775, true);
        }

        $destPath  = realpath($destPath);
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $em->getProxyFactory()->generateProxyClasses($metadatas, $destPath);

        // Réattribuer les permissions
        $filesystem->chmod($cachePath, 0777, 0000, true);
    }

}