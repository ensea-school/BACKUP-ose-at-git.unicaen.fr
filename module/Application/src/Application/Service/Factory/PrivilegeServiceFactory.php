<?php

namespace Application\Service\Factory;

use Application\Constants;
use Application\Service\PrivilegeService;
use Zend\ServiceManager\ServiceLocatorInterface as ContainerInterface;
use Application\Service\PlafondService;



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
        $service = new PrivilegeService();
        $service->setEntityManager($container->get(Constants::BDD));

        return $service;
    }
}