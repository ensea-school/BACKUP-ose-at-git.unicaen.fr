<?php

namespace Application\Service\Factory;

use Application\Constants;
use Application\Service\PrivilegeService;
use UnicaenAuth\Entity\Db\Privilege;
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
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}