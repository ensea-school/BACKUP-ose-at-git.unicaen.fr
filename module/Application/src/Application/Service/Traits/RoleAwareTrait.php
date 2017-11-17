<?php

namespace Application\Service\Traits;

use Application\Service\Role;

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
     *
     * @return self
     */
    public function setServiceRole(Role $serviceRole)
    {
        $this->serviceRole = $serviceRole;

        return $this;
    }



    /**
     * @return Role
     */
    public function getServiceRole()
    {
        if (empty($this->serviceRole)) {
            $this->serviceRole = \Application::$container->get('ApplicationRole');
        }

        return $this->serviceRole;
    }
}