<?php

namespace Application\Provider\Privilege;

use Common\Exception\RuntimeException;

trait PrivilegeProviderAwareTrait
{
    /**
     * description
     *
     * @var PrivilegeProviderInterface
     */
    private $privilegeProvider;

    /**
     *
     * @param PrivilegeProviderInterface $privilegeProvider
     * @return self
     */
    public function setPrivilegeProvider( PrivilegeProviderInterface $privilegeProvider )
    {
        $this->privilegeProvider = $privilegeProvider;
        return $this;
    }

    /**
     *
     * @return PrivilegeProviderInterface
     * @throws \Common\Exception\RuntimeException
     */
    public function getPrivilegeProvider()
    {
        if (empty($this->privilegeProvider)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('PrivilegeProvider');
        }else{
            return $this->privilegeProvider;
        }
    }

}