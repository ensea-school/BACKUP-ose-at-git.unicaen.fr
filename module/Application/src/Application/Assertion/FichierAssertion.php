<?php

namespace Application\Assertion;

use Application\Entity\Db\Fichier;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of FichierAssertion
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class FichierAssertion extends OldAbstractAssertion implements /*FichierServiceAwareInterface,*/ WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    
    const PRIVILEGE_VALIDER     = 'valider';
    const PRIVILEGE_DEVALIDER   = 'devalider';
    const PRIVILEGE_TELECHARGER = 'telecharger';
    
    /**
     * @var Fichier
     */
    protected $resource;
    
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
        
        /**
         * Cas N°1 : la ressource spécifiée est une entité ; un privilège est spécifié.
         */
        if ($resource instanceof Fichier) {
            return $this->assertEntityOld();
        }
        
        /**
         * Cas N°2 : la ressource spécifiée est une chaîne de caractères du type 'controller/Application\Controller\Fichier:action' ;
         * un privilège est spécifié (config des pages de navigation) ou pas (config des controller guards BjyAuthorize).
         */
        
        $privilege = $this->normalizedPrivilege($privilege, $resource);
        
        return true;
    }
    
    /**
     * 
     * @return boolean
     */
    protected function assertEntityOld()
    {
        if (!parent::assertCRUD()) {
            return false;
        }
        
        // Impossible de supprimer un fichier validé
        if ($this->privilege === self::PRIVILEGE_DELETE && $this->resource->getValidation()) {
            return false;
        }
        
        // Impossible de valider un fichier déjà validé
        if ($this->privilege === self::PRIVILEGE_VALIDER && $this->resource->getValidation()) {
            return false;
        }
        
        // Impossible de dévalider un fichier non encore validé
        if ($this->privilege === self::PRIVILEGE_DEVALIDER && !$this->resource->getValidation()) {
            return false;
        }

        return true;
    }
}