<?php

namespace Administration\Service;

use Plafond\Service\PlafondServiceAwareTrait;
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
    use PlafondServiceAwareTrait;

    public function clearCache(): void
    {
        $em = $this->getEntityManager();

        $filesystem = new Filesystem();

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

        $destPath  = realpath($destPath);
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $em->getProxyFactory()->generateProxyClasses($metadatas, $destPath);

        // Réattribuer les permissions
        $filesystem->chmod($cachePath, 0777, 0000, true);
    }



    public function calcData()
    {
// Mise à jour du cache des structures
        $this->getEntityManager()->getConnection()->executeQuery('BEGIN OSE_DIVERS.UPDATE_STRUCTURES(); END;');

        $this->getServicePlafond()->construire();

        /** @var WorkflowController $wf */
        $c->begin('Mise à jour des tableaux de bords');
        $wf = $oa->getController(WorkflowController::class);
        $wf->calculTableauxBordAction();


    }
}