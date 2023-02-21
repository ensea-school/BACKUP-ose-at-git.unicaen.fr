<?php

namespace OffreFormation\Service\Factory;

use Psr\Container\ContainerInterface;
use OffreFormation\Service\DisciplineService;


/**
 * Description of DisciplineServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class DisciplineServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return DisciplineService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): DisciplineService
    {
        $service = new DisciplineService;

        /* Injectez vos dépendances ICI */

        return $service;
    }
}

