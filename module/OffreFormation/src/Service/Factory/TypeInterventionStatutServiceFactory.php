<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\TypeInterventionStatutService;


/**
 * Description of TypeInterventionStatutServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class TypeInterventionStatutServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return TypeInterventionStatutService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): TypeInterventionStatutService
    {
        $service = new TypeInterventionStatutService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

