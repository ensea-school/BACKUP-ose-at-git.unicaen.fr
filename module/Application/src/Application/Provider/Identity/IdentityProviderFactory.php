<?php

namespace Application\Provider\Identity;

use Application\Service\ContextService;
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
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $identityProvider = new IdentityProvider;

        $identityProvider->setEntityManager($container->get(\Application\Constants::BDD));
        $identityProvider->setServiceContext($container->get(ContextService::class));
        $identityProvider->setHostLocalization($container->get('HostLocalization'));

        return $identityProvider;
    }
}