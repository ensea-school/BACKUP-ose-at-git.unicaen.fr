<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\TypeInterventionStructureService;


/**
 * Description of TypeInterventionStructureServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionStructureServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionStructureService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionStructureService
    {
        $service = new TypeInterventionStructureService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

