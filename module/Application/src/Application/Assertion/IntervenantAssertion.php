<?php

namespace Application\Assertion;

use Application\Entity\Db\Intervenant;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 *
 *
 * @author Laurent LÉCLUSE <laurent.lecluse at unicaen.fr>
 */
class IntervenantAssertion extends AbstractAssertion
{
    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl               $acl
     * @param  RoleInterface     $role
     * @param  ResourceInterface $resource
     * @param  string            $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        parent::assert($acl, $role, $resource, $privilege);
        
        $privilege = $this->normalizedPrivilege($privilege, $resource);
        
        if ('total-heures-comp' == $privilege){
            $resource = $this->getMvcEvent()->getParam('intervenant');
            return $this->assertIntervenantTotalHeuresComp($this->getSelectedIdentityRole(), $resource);
        }
        
        return true;
    }

    /**
     *
     * @param RoleInterface $role
     * @param Intervenant $resource
     * @return boolean
     */
    protected function assertIntervenantTotalHeuresComp(RoleInterface $role = null, Intervenant $resource = null)
    {
        /*********************************************************
         *                      Rôle intervenant
         *********************************************************/
        if ($role instanceof \Application\Acl\IntervenantRole){
            if ($role->getIntervenant() <> $resource){
                return false;
            }
        }

        /*********************************************************
         *                      Tous les autres
         *********************************************************/
        return true;
    }
}