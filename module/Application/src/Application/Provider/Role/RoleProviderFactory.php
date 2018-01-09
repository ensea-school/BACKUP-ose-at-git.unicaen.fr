<?php

namespace Application\Provider\Role;

use Application\Service\ContextService;
use Application\Service\PersonnelService;
use Application\Service\StatutIntervenantService;
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
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextServiceAwareTrait;



    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return Acteur
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $sl = $serviceLocator;
        $this->setServiceLocator($serviceLocator);

        $config = $this->getServiceLocator()->get('BjyAuthorize\Config');
        $em     = $this->getServiceLocator()->get(\Application\Constants::BDD);
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
            ->setServicePersonnel($sl->get(PersonnelService::class))
            ->setServiceStatutIntervenant($sl->get(StatutIntervenantService::class))
            ->setServiceContext($sl->get(ContextService::class))
            ->setPrivilegeProvider($sl->get('UnicaenAuth\Privilege\PrivilegeProvider'))
            ->setStructureSelectionnee($this->getServiceContext()->getStructure(true));

        return $roleProvider;
    }
}