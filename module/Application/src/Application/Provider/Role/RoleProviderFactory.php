<?php

namespace Application\Provider\Role;

use InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * 
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class RoleProviderFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Acteur
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config                 = $serviceLocator->get('BjyAuthorize\Config');
        $em                     = $serviceLocator->get('doctrine.entitymanager.orm_default'); /* @var $em \Doctrine\ORM\EntityManager */

        if (! isset($config['role_providers']['ApplicationRoleProvider'])) {
            throw new InvalidArgumentException(
                'Config for "ApplicationRoleProvider" not set'
            );
        }

        $providerConfig = $config['role_providers']['ApplicationRoleProvider'];

        $roleProvider = new RoleProvider( $providerConfig );
        $roleProvider->setEntityManager($em);
        $roleProvider->init();
        return $roleProvider;
    }
}