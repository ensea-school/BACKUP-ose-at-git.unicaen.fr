<?php

namespace Application\Provider\Role;

use Application\Service\ContextService;
use Application\Service\StatutIntervenantService;
use Application\Service\Traits\ContextServiceAwareTrait;
use InvalidArgumentException;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class RoleProviderFactory implements FactoryInterface
{
    use ContextServiceAwareTrait;



    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Acteur
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('BjyAuthorize\Config');
        $em     = $serviceLocator->get(\Application\Constants::BDD);
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
            ->setServiceStatutIntervenant($serviceLocator->get(StatutIntervenantService::class))
            ->setServiceContext($serviceLocator->get(ContextService::class))
            ->setPrivilegeProvider($serviceLocator->get('UnicaenAuth\Privilege\PrivilegeProvider'))
            ->setStructureSelectionnee($this->getServiceContext()->getStructure(true));

        return $roleProvider;
    }
}