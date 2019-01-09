<?php

namespace Application\Interfaces;

use Application\Entity\Db\Role;

/**
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
interface RoleAwareInterface
{

    /**
     * Spécifie le rôle concerné.
     *
     * @param Role $role Rôle concernée
     */
    public function setRole(Role $Role = null);

    /**
     * Retourne le rôle concerné.
     *
     * @return Role
     */
    public function getRole();
}