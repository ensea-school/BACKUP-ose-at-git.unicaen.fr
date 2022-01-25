<?php

namespace Application\Provider\Role;

use Application\Service\ContextService;
use Application\Service\StatutService;
use Application\Service\Traits\ContextServiceAwareTrait;
use Psr\Container\ContainerInterface;
use InvalidArgumentException;

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
        $config = $container->get('BjyAuthorize\Config');
        $em     = $container->get(\Application\Constants::BDD);
        /* @var $em \Doctrine\ORM\EntityManager */

        if (!isset($config['role_providers'][RoleProvider::class])) {
            throw new InvalidArgumentException(
                'Config for "ApplicationRoleProvider" not set'
            );
        }

        $providerConfig = $config['role_providers'][RoleProvider::class];

        $roleProvider = new RoleProvider($providerConfig);
        $roleProvider
            ->setEntityManager($em)
            ->setServiceStatutIntervenant($container->get(StatutService::class))
            ->setServiceContext($container->get(ContextService::class))
            ->setPrivilegeProvider($container->get('UnicaenAuth\Privilege\PrivilegeProvider'));

        return $roleProvider;
    }
}