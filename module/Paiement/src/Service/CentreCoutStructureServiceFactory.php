<?php

namespace Paiement\Service;

use Psr\Container\ContainerInterface;



/**
 * Description of CentreCoutStructureServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class CentreCoutStructureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return CentreCoutStructureService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): CentreCoutStructureService
    {
        $service = new CentreCoutStructureService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}