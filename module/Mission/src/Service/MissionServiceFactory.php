<?php

namespace Mission\Service;

use Application\Constants;
use Psr\Container\ContainerInterface;



/**
 * Description of MissionServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class MissionServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return MissionService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): MissionService
    {
        $service = new MissionService;
        $service->setEntityManager($container->get(Constants::BDD));

        /* Injectez vos dépendances ICI */

        return $service;
    }
}