<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Role;

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
    private $role;





    /**
     * @param Role $role
     * @return self
     */
    public function setRole( Role $role = null )
    {
        $this->role = $role;
        return $this;
    }



    /**
     * @return Role
     */
    public function getRole()
    {
        return $this->role;
    }
}