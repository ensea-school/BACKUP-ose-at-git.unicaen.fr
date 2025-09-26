<?php

namespace Utilisateur\Provider;

use Application\Service\ContextService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Doctrine\ORM\EntityManager;
use Intervenant\Service\StatutService;
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


    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
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