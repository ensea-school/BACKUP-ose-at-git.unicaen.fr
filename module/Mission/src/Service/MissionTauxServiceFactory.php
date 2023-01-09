<?php

namespace Mission\Service;

use Mission\Entity\Db\MissionTauxRemu;
use Psr\Container\ContainerInterface;


/**
 * Description of MissionTauxServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTauxServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionTauxService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTauxService
    {
        $service = new MissionTauxService;

        /* Injectez vos dépendances ICI */
        return $service;
    }
}

