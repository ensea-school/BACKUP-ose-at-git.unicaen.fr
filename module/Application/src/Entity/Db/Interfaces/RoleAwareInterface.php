<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Role;

/**
 * Description of RoleAwareInterface
 *
 * @author UnicaenCode
 */
interface RoleAwareInterface
{
    /**
     * @param Role $role
     * @return self
     */
    public function setRole( Role $role = null );



    /**
     * @return Role
     */
    public function getRole();
}