<?php

namespace Application\Service\Factory;

use Application\Constants;
use Application\Service\PrivilegeService;
use UnicaenAuth\Entity\Db\Privilege;
use Interop\Container\ContainerInterface;



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


        $config = $container->get('Config');

        if (! isset($config['unicaen-auth']['privilege_entity_class'])) {
            $config['unicaen-auth']['privilege_entity_class'] = Privilege::class;
        }
        $service->setPrivilegeEntityClass($config['unicaen-auth']['privilege_entity_class']);

        return $service;
    }
}