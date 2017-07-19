<?php

namespace Application\Provider\Identity;

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
        $identityProvider->setServiceIntervenant($serviceLocator->get('applicationIntervenant'));
        $identityProvider->setServicePersonnel($serviceLocator->get('applicationPersonnel'));
        $identityProvider->setServiceUserContext($serviceLocator->get('AuthUserContext'));

        return $identityProvider;
    }
}