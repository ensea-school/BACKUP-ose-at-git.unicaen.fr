<?php

namespace Mission\Service;

use Psr\Container\ContainerInterface;


/**
 * Description of MissionTauxServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxValeurServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTauxValeurService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTauxValeurService
    {
        $service = new MissionTauxValeurService;

        /* Injectez vos dépendances ICI */
        return $service;
    }
}

