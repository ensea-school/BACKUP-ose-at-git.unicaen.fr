<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\TypeFormationService;


/**
 * Description of TypeFormationServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeFormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeFormationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeFormationService
    {
        $service = new TypeFormationService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

