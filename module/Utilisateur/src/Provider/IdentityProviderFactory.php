<?php

namespace Utilisateur\Provider;

use Application\Service\ContextService;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

/**
 *
 *
 */
class IdentityProviderFactory
{
    /**
     * Create an object
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param null|array         $options
     *
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $identityProvider = new IdentityProvider;

        $identityProvider->setEntityManager($container->get(EntityManager::class));
        $identityProvider->setServiceContext($container->get(ContextService::class));
        $identityProvider->setHostLocalization($container->get('HostLocalization'));

        return $identityProvider;
    }
}