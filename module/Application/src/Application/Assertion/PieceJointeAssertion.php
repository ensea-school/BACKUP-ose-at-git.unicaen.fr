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
class PieceJointeAssertion extends OldAbstractAssertion implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    
    const PRIVILEGE_VALIDER        = 'valider';
    const PRIVILEGE_DEVALIDER      = 'devalider';
    const PRIVILEGE_CREATE_FICHIER = 'create-fichier';
    
    /**
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
        if ($resource instanceof PieceJointe) {
            return $this->assertEntityOld($acl, $role, $resource, $privilege);
        }
        
        /**
         * Cas N°2 : la ressource spécifiée est une chaîne de caractères du type 'controller/Application\Controller\PieceJointe:action' ;
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
        
        // Impossible de supprimer une PJ validée
        if ($this->privilege === self::PRIVILEGE_DELETE && $this->resource->getValidation()) {
            return false;
        }
        
        // Impossible de valider une PJ déjà validée
        if ($this->privilege === self::PRIVILEGE_VALIDER && $this->resource->getValidation()) {
            return false;
        }
        
        // Impossible de dévalider une PJ non encore validée
        if ($this->privilege === self::PRIVILEGE_DEVALIDER && !$this->resource->getValidation()) {
            return false;
        }
        // Impossible de valider une PJ sans fichier associé
        if ($this->privilege === self::PRIVILEGE_VALIDER && ! count($this->resource->getFichier())) {
            return false;
        }
        
        // Impossible d'ajouter un fichier à une PJ validée
        if ($this->privilege === self::PRIVILEGE_CREATE_FICHIER && $this->resource->getValidation()) {
            return false;
        }
        
        /*********************************************************
         *              Rôle Intervenant extérieur
         *********************************************************/
        if ($this->role instanceof IntervenantExterieurRole) {
            
            // cas imprévu
            if (!$this->role->getIntervenant()->getDossier()) {
                throw new LogicException("Anomalie rencontrée : l'intervenant n'a pas de dossier.");
            }
            
            // Un intervenant ne peut manipuler que ses PJ !
            if ($this->resource->getDossier() !== $this->role->getIntervenant()->getDossier()) {
                return false;
            }
        }

        return true;
    }
}