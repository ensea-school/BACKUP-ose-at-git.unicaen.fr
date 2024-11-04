<?php

namespace Administration\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;



/**
 * Description of AdministrationServiceFactory
 *
 * @author Laurent Lécluse <laurent.lecluse at unicaen.fr>
 */
class AdministrationServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return AdministrationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null): AdministrationService
    {
        $service = new AdministrationService;

        $service->setEntityManager($container->get(EntityManager::class));

        return $service;
    }
}