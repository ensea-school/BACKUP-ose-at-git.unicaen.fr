<?php

namespace Application\Assertion;

use Application\Acl\IntervenantExterieurRole;
use Application\Entity\Db\PieceJointe;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Common\Exception\LogicException;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of PieceJointeAssertion
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class PieceJointeAssertion extends AbstractAssertion implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    
    const PRIVILEGE_VALIDER        = 'valider';
    const PRIVILEGE_DEVALIDER      = 'devalider';
    const PRIVILEGE_CREATE_FICHIER = 'create-fichier';

    /**
     * @var PieceJointe
     */
    protected $pj;
    
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
        /**
         * Cas N°1 : la ressource spécifiée est une entité ; un privilège est spécifié.
         */
        if ($resource instanceof PieceJointe) {
            return $this->assertEntity($acl, $role, $resource, $privilege);
        }
        
        /**
         * Cas N°2 : la ressource spécifiée est une chaîne de caractères du type 'controller/Application\Controller\PieceJointe:action' ;
         * un privilège est spécifié (config des pages de navigation) ou pas (config des controller guards BjyAuthorize).
         */
        
        $privilege = $this->normalizedPrivilege($privilege, $resource);
        
//        var_dump(__CLASS__ . ' >>> ' . $resource . ' : ' . $privilege);
        
        return true;
    }
    
    /**
     * 
     * @param Acl $acl
     * @param RoleInterface $role
     * @param ResourceInterface $resource
     * @param string $privilege
     * @return boolean
     */
    protected function assertEntity(Acl $acl, RoleInterface $role = null, ResourceInterface $resource = null, $privilege = null)
    {
        if (!parent::assertCRUD($acl, $role, $resource, $privilege)) {
            return false;
        }
        
        $this->pj = $resource;
        $role     = $this->getSelectedIdentityRole();
        
        // Impossible de supprimer une PJ validée
        if ($privilege === self::PRIVILEGE_DELETE && $this->pj->getValidation()) {
            return false;
        }
        
        // Impossible de valider une PJ déjà validée
        if ($privilege === self::PRIVILEGE_VALIDER && $this->pj->getValidation()) {
            return false;
        }
        
        // Impossible de dévalider une PJ non encore validée
        if ($privilege === self::PRIVILEGE_DEVALIDER && !$this->pj->getValidation()) {
            return false;
        }
        
        // Impossible d'ajouter un fichier à une PJ validée
        if ($privilege === self::PRIVILEGE_CREATE_FICHIER && $this->pj->getValidation()) {
            return false;
        }
        
        /*********************************************************
         *              Rôle Intervenant extérieur
         *********************************************************/
        if ($role instanceof IntervenantExterieurRole) {
            
            // cas imprévu
            if (!$role->getIntervenant()->getDossier()) {
                throw new LogicException("Anomalie rencontrée : l'intervenant n'a pas de dossier.");
            }
            
            // Un intervenant ne peut manipuler que ses PJ !
            if ($this->pj->getDossier() !== $role->getIntervenant()->getDossier()) {
                return false;
            }
        }
        
        /*********************************************************
         *                   Rôle Composante
         *********************************************************/
//        if ($role instanceof ComposanteRole) {
//            
//        }

        return true;
    }
    
    /**
     * 
     * @param string $privilege
     * @param string $resource
     * @return string
     */
    protected function normalizedPrivilege($privilege, $resource)
    {
        if (is_object($privilege)) {
            return $privilege;
        }
        if (!$privilege) {
            $privilege = ($tmp = strrchr($resource, $c = ':')) ? ltrim($tmp, $c) : null;
        }
        
        return $privilege;
    }
}