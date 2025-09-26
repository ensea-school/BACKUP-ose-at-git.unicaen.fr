<?php

namespace Utilisateur\Service;

use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;


/**
 * Description of PrivilegeServiceFactory
 *
 * @author LECLUSE Laurent <laurent.lecluse at unicaen.fr>
 */
class PrivilegeServiceFactory
{

    /**
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PrivilegeService
     */
    public function __invoke(ContainerInterface $container, $requestedName, $options = null)
    {
        $config = $container->get('Config');

        if (isset($config['application']['privileges'])) {
            $privilegesRolesConfig = $config['application']['privileges'];
        } else {
            $privilegesRolesConfig = [];
        }

        $service = new PrivilegeService($privilegesRolesConfig);
        $service->setEntityManager($container->get(EntityManager::class));
        //$service->setPrivilegeEntityClass(Privilege::class);

        return $service;
    }
}