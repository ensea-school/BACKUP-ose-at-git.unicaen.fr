<?php

namespace Application\Provider\Identity;

use BjyAuthorize\Service\Authorize;
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
     * @return IdentityProvider
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default'); /* @var $em \Doctrine\ORM\EntityManager */
        
        $identityProvider = new IdentityProvider;
        $identityProvider->setEntityManager($em);

        $bs = $serviceLocator->get('BjyAuthorize\Service\Authorize');
        /* @var $bs Authorize */
        $roles = $bs->getAcl()->getRoles();

        return $identityProvider;
    }
}