<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\TypeInterventionService;


/**
 * Description of TypeInterventionServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionService
    {
        $service = new TypeInterventionService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

