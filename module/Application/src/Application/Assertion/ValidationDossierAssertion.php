<?php

namespace Application\Assertion;

use Application\Entity\Db\Validation as ValidationEntity;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Assertions concernant la validation des données personnelles (dossier).
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationDossierAssertion extends OldAbstractAssertion
{
    /**
     * @var ValidationEntity 
     */
    protected $resource;
    
    /**
     * Returns true if and only if the assertion conditions are met
     *
     * This method is passed the ACL, Role, Resource, and privilege to which the authorization query applies. If the
     * $role, $resource, or $privilege parameters are null, it means that the query applies to all Roles, Resources, or
     * privileges, respectively.
     *
     * @param  Acl                        $acl
     * @param  RoleInterface         $role
     * @param  ResourceInterface $resource
     * @param  string                         $privilege
     * @return bool
     */
    public function assert(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        parent::assert($acl, $role, $resource, $privilege);
        
        if ($resource instanceof ValidationEntity) {
            return $this->assertEntityOld();
        }
        
        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    protected function assertEntityOld()
    {
        return true;
    }
}