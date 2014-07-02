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
        $serviceRole            = $serviceLocator->get('applicationRole');            /* @var $serviceRole \Application\Service\Role */
        $serviceUtilisateurRole = $serviceLocator->get('applicationRoleUtilisateur'); /* @var $serviceUtilisateurRole \Application\Service\RoleUtilisateur */
        $config                 = $serviceLocator->get('BjyAuthorize\Config');

        if (! isset($config['role_providers']['ApplicationRoleProvider'])) {
            throw new InvalidArgumentException(
                'Config for "ApplicationRoleProvider" not set'
            );
        }

        $providerConfig = $config['role_providers']['ApplicationRoleProvider'];
        
        return new RoleProvider($serviceRole, $serviceUtilisateurRole, $providerConfig);
    }
}