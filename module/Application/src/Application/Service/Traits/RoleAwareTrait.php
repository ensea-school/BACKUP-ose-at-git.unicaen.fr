<?php

namespace Application\Service\Traits;

use Application\Service\Role;
use Application\Module;
use RuntimeException;

/**
 * Description of RoleAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleAwareTrait
{
    /**
     * @var Role
     */
    private $serviceRole;





    /**
     * @param Role $serviceRole
     * @return self
     */
    public function setServiceRole( Role $serviceRole )
    {
        $this->serviceRole = $serviceRole;
        return $this;
    }



    /**
     * @return Role
     * @throws RuntimeException
     */
    public function getServiceRole()
    {
        if (empty($this->serviceRole)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->serviceRole = $serviceLocator->get('ApplicationRole');
        }
        return $this->serviceRole;
    }
}