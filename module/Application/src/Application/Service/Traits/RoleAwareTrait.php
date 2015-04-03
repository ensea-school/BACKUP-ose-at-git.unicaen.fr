<?php

namespace Application\Service\Traits;

use Application\Service\Role;
use Common\Exception\RuntimeException;

trait RoleAwareTrait
{
    /**
     * description
     *
     * @var Role
     */
    private $serviceRole;

    /**
     *
     * @param Role $serviceRole
     * @return self
     */
    public function setServiceRole( Role $serviceRole )
    {
        $this->serviceRole = $serviceRole;
        return $this;
    }

    /**
     *
     * @return Role
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceRole()
    {
        if (empty($this->serviceRole)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationRole');
        }else{
            return $this->serviceRole;
        }
    }

}