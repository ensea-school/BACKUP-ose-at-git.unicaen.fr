<?php

namespace Application\Assertion;

use Application\Acl\ComposanteRole;
use Application\Acl\IntervenantRole;
use Application\Entity\Db\Contrat;
use Application\Entity\Db\WfEtape;
use Application\Service\Traits\WorkflowServiceAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Contrat
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratAssertionOld extends OldAbstractAssertion
{
    use WorkflowServiceAwareTrait;

    const PRIVILEGE_EXPORTER    = 'exporter';
    const PRIVILEGE_VALIDER     = 'valider';
    const PRIVILEGE_DEVALIDER   = 'devalider';
    const PRIVILEGE_DATE_RETOUR = 'date_retour';
    const PRIVILEGE_DEPOSER     = 'deposer';

    /**
     * @var Contrat
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
        
        if ($resource instanceof Contrat) {
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
        if (!parent::assertCRUD()) {
            return false;
        }
        
        // Impossible de supprimer un contrat/avenant validé
        if ($this->privilege === self::PRIVILEGE_DELETE && $this->resource->getValidation()) {
            return false;
        }
        
        // Impossible de valider un contrat/avenant déjà validé
        if ($this->privilege === self::PRIVILEGE_VALIDER && $this->resource->getValidation()) {
            return false;
        }
        
        // Impossible de dévalider un contrat
        if ($this->privilege === self::PRIVILEGE_DEVALIDER && !$this->resource->estUnAvenant()) {
            return false;
        }
        
        // Impossible de dévalider un avenant non encore validé
        if ($this->privilege === self::PRIVILEGE_DEVALIDER && !$this->resource->getValidation()) {
            return false;
        }
        
        // Impossible d'ajouter/supprimer un fichier à un PROJET de contrat/avenant
        if ($this->privilege === self::PRIVILEGE_DEPOSER && $this->resource->estUnProjet()) {
            return false;
        }
        
        // Impossible de modifier la date de retour signé d'un PROJET de contrat/avenant
        if ($this->privilege === self::PRIVILEGE_DATE_RETOUR && $this->resource->estUnProjet()) {
            return false;
        }
        
        /*********************************************************
         *                      Rôle Intervenant
         *********************************************************/
        if ($this->role instanceof IntervenantRole) {
            
            // l'intervenant n'a pas accès à un contrat/avenant d'un autre
            if ($this->resource->getIntervenant() !== $this->role->getIntervenant()) {
                return false;
            }
            
            // l'intervenant n'a pas accès aux projets de contrat/avenant
            if (! $this->resource->getValidation()) {
                return false;
            }
        }
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        elseif (
                $this->role instanceof ComposanteRole
                || $this->role instanceof AdministrateurRole && $this->role->getStructure()
        ) {
            
            // structure de responsabilité de l'utilisateur et structure du contrat doivent correspondre
            if ($this->role->getStructure() !== $this->resource->getStructure()) {
                return false;
            }

            $wfOk = $this->getServiceWorkflow()->getEtape(
                WfEtape::CODE_CONTRAT,
                $this->resource->getIntervenant(),
                $this->resource->getStructure()
            )->isAtteignable();


            // l'étape Contrat du workflow doit être atteignable
            if (!$wfOk) {
                return false;
            }

        }
        
        return true;
    }

}