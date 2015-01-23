<?php

namespace Application\Assertion;

use Application\Acl\AdministrateurRole;
use Application\Acl\ComposanteRole;
use Application\Acl\DrhRole;
use Application\Acl\EtablissementRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\Validation as ValidationEntity;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Assertions concernant les validations.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ValidationReferentielAssertion extends AbstractAssertion
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
            return $this->assertEntity();
        }
        
        return true;
    }
    
    /**
     * @return boolean
     */
    protected function assertEntity()
    {
        /*********************************************************
         *                      Rôle administrateur
         *********************************************************/
        if ($this->role instanceof AdministrateurRole) {
            return true;
        }

        $structureIntervenant = $this->resource->getIntervenant()->getStructure();

        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->role instanceof ComposanteRole) {
            if ('read' === $this->privilege) {
                return true; // les composantes voient tout
            }
            
            $structureRole = $this->role->getStructure();
            
            if ($structureRole === $structureIntervenant) {
                return true;
            }
        }

        /*********************************************************
         *                      Rôle Superviseur
         *********************************************************/
        if ($this->role instanceof EtablissementRole) {
            if ('read' === $this->privilege) {
                return true; // les superviseurs voient tout
            }
        }

        /*********************************************************
         *                      Rôle DRH
         *********************************************************/
        if ($this->role instanceof DrhRole) {
            if ('read' === $this->privilege) {
                return true; // ils voient tout à la DRH
            }
        }

        /*********************************************************
         *                      Rôle Intervenant
         *********************************************************/
        if ($this->role instanceof IntervenantRole) {
            return $this->assertEntityForIntervenantRole();
        }
        
        return false;
    }
    
    /**
     * @return boolean
     */
    protected function assertEntityForIntervenantRole()
    {
        if ('read' === $this->privilege) {
            return true;
        }
        
        return false;
    }
}