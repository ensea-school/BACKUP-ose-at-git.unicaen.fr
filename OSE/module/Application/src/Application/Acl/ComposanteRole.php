<?php

namespace Application\Acl;

use Zend\Permissions\Acl\Role\RoleInterface;
use BjyAuthorize\Acl\HierarchicalRoleInterface;

/**
 * Rôle père de tous les rôles "composante".
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ComposanteRole implements RoleInterface, HierarchicalRoleInterface
{
    const ROLE_ID = 'composante';
        
    /**
     * Returns the string identifier of the Role
     *
     * @return string
     */
    public function getRoleId()
    {
        return self::ROLE_ID;
    }
    
    /**
     * Get the parent role
     *
     * @return \Zend\Permissions\Acl\Role\RoleInterface|null
     */
    public function getParent()
    {
        return 'user';
    }
}