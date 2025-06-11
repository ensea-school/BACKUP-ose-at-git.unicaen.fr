<?php

namespace OffreFormation\Form\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Form\VolumeHoraireEnsForm;


/**
 * Description of VolumeHoraireEnsFormFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireEnsFormFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return VolumeHoraireEnsForm
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $formOffreFormationVolumeHoraireEns = new VolumeHoraireEnsForm;

        /* Injectez vos dépendances ICI */

        return $formOffreFormationVolumeHoraireEns;
    }
}