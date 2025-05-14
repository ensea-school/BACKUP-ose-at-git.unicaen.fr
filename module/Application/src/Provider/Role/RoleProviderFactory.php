<?php

namespace Application\Provider\Role;

use Application\Service\ContextService;
use Doctrine\ORM\EntityManager;
use Intervenant\Service\StatutService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Psr\Container\ContainerInterface;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleProviderFactory
{
    use ContextServiceAwareTrait;


    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $roleProvider = new RoleProvider();
        $roleProvider
            ->setEntityManager($container->get(EntityManager::class))
            ->setServiceStatut($container->get(StatutService::class))
            ->setServiceContext($container->get(ContextService::class))
            ->setPrivilegeProvider($container->get(PrivilegeService::class));

        return $roleProvider;
    }
}