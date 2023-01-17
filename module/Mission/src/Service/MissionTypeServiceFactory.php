<?php

namespace Mission\Service;

use Mission\Entity\Db\TypeMission;
use Psr\Container\ContainerInterface;


/**
 * Description of MissionTypeServiceFactory
 *
 * @author Florian Joriot <florian.joriot at unicaen.fr>
 */
class MissionTypeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null               $options
     *
     * @return MissionTypeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionTypeService
    {
        $service = new MissionTypeService;

        /* Injectez vos dépendances ICI */
        return $service;
    }
}

