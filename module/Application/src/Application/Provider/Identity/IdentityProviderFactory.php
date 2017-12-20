<?php

namespace Application\Provider\Identity;

use Application\Service\ContextService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class IdentityProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return IdentityProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $identityProvider = new IdentityProvider;

        $identityProvider->setEntityManager( $serviceLocator->get(\Application\Constants::BDD) );
        $identityProvider->setServicePersonnel($serviceLocator->get('applicationPersonnel'));
        $identityProvider->setServiceContext($serviceLocator->get(ContextService::class));

        return $identityProvider;
    }
}