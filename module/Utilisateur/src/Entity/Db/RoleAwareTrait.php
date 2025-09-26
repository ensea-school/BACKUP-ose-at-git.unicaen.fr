<?php

namespace Utilisateur\Entity\Db;

/**
 * Description of RoleAwareTrait
 *
 * @author UnicaenCode
 */
trait RoleAwareTrait
{
    protected ?Role $role = null;



    /**
     * @param Role $role
     *
     * @return self
     */
    public function setRole( ?Role $role )
    {
        $this->role = $role;

        return $this;
    }



    public function getRole(): ?Role
    {
        return $this->role;
    }
}