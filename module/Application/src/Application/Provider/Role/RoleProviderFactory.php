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
    use \Zend\ServiceManager\ServiceLocatorAwareTrait,
        \Application\Service\Traits\ContextAwareTrait
    ;

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Acteur
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->setServiceLocator($serviceLocator);

        $config                 = $this->getServiceLocator()->get('BjyAuthorize\Config');
        $em                     = $this->getServiceLocator()->get('doctrine.entitymanager.orm_default'); /* @var $em \Doctrine\ORM\EntityManager */

        if (! isset($config['role_providers']['ApplicationRoleProvider'])) {
            throw new InvalidArgumentException(
                'Config for "ApplicationRoleProvider" not set'
            );
        }

        $providerConfig = $config['role_providers']['ApplicationRoleProvider'];

        $roleProvider = new RoleProvider( $providerConfig );
        $roleProvider
                ->setEntityManager($em)
                ->setServiceLocator($this->getServiceLocator())
                ->setStructureSelectionnee($this->getServiceContext()->getStructure(true))
                ->init();
        return $roleProvider;
    }
}