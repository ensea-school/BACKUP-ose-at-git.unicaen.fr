<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\GroupeTypeFormationService;


/**
 * Description of GroupeTypeFormationServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class GroupeTypeFormationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return GroupeTypeFormationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): GroupeTypeFormationService
    {
        $service = new GroupeTypeFormationService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

