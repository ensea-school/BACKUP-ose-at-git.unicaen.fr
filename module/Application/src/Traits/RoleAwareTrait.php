<?php

namespace Application\Traits;

use Laminas\Permissions\Acl\Role\RoleInterface;

/**
 * Description of RoleAwareTrait
 *
 */
trait RoleAwareTrait
{
    /**
     * @var RoleInterface
     */
    protected $role;



    /**
     * Spécifie le rôle courant.
     *
     * @param RoleInterface $role
     */
    public function setRole(RoleInterface $role)
    {
        $this->role = $role;

        return $this;
    }



    /**
     * Retourne le rôle courant.
     *
     * @return RoleInterface
     */
    public function getRole(): RoleInterface
    {
        return $this->role;
    }
}
