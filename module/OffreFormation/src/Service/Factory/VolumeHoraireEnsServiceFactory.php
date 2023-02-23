<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\VolumeHoraireEnsService;


/**
 * Description of VolumeHoraireEnsServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class VolumeHoraireEnsServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return VolumeHoraireEnsService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): VolumeHoraireEnsService
    {
        $service = new VolumeHoraireEnsService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

