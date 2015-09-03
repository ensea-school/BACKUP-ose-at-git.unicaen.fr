<?php

namespace Application\Assertion;

use Application\Acl\ComposanteRole;
use Application\Controller\AgrementController;
use Application\Entity\Db\Agrement;
use Application\Entity\Db\TypeAgrement;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Service\Traits\AgrementAwareTrait;
use Application\Service\Workflow\WorkflowIntervenant;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Agrement
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementAssertion extends AbstractAssertion implements WorkflowIntervenantAwareInterface
{
    use AgrementAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var Agrement
     */
    protected $agrement;
    
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
        if ($resource instanceof Agrement) {
            return $this->assertEntityOld();
        }
        
        /**
         * Cas N°2 : la ressource spécifiée est une chaîne de caractères du type 'controller/Application\Controller\Agrement:action' ;
         * un privilège est spécifié (config des pages de navigation) ou pas (config des controller guards BjyAuthorize).
         */
        
        $privilege = $this->normalizedPrivilege($privilege, $resource);
        
        $privilegeAjouterLotConseilRestreint  = sprintf("%s/%s", AgrementController::ACTION_AJOUTER_LOT, TypeAgrement::CODE_CONSEIL_RESTREINT);
        $privilegeAjouterLotConseilAcademique = sprintf("%s/%s", AgrementController::ACTION_AJOUTER_LOT, TypeAgrement::CODE_CONSEIL_ACADEMIQUE);

        // l'ajout par lot d'agréments de type "Conseil Académique" n'est autorisé qu'aux admin
        if ($privilege === $privilegeAjouterLotConseilAcademique) {
            if ($this->role->getRoleId() !== \Application\Acl\AdministrateurRole::ROLE_ID) {
                return false;
            }
        }
        // l'ajout par lot d'agréments de type "Conseil Restreint" n'est pas autorisé aux admin pour
        // l'instant car cela nécessiterait la sélection de la composante concernée
        elseif ($privilege === $privilegeAjouterLotConseilRestreint) {
            if ($this->role->getRoleId() === \Application\Acl\AdministrateurRole::ROLE_ID) {
                return false;
            }
        }
        
        return true;
    }
    
    /**
     * @see AbstractAssertion
     */
    protected function normalizedPrivilege($privilege, $resource)
    {
        if (is_object($resource)) {
            return $privilege;
        }
        
        $privilege = parent::normalizedPrivilege($privilege, $resource);
        
        // concaténation du type d'agrément concerné
        if ($privilege && false === strpos($privilege, '/') && $this->getTypeAgrement()) {
            $privilege .= '/' . $this->getTypeAgrement()->getCode();
        }
        
        return $privilege;
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
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->role instanceof ComposanteRole) {
            
            // saisie de l'agrément Conseil Academique interdit aux gestionnaires de composante
            if ($this->resource->getType()->isConseilAcademique()) {
                return false;
            }
            
            // structure de responsabilité de l'utilisateur et structure de l'agrément doivent correspondre
            if ($this->role->getStructure() !== $this->resource->getStructure()) {
//            if ($this->resource->getStructure() && $this->role->getStructure()->getId() !== $this->resource->getStructure()->getId()) {
                return false;
            }
        }
        
        $agrementStepKey = $this->resource->getType()->getCode();
        
        // l'étape Agrement du workflow doit être atteignable
        if (!$this->getWorkflow()->isStepReachable($agrementStepKey)) {
            return false;
        }

        /**
         * Modification, suppression
         */
        if (in_array($this->privilege, ['update', 'delete'])) {
            // l'étape suivante du workflow ne doit pas avoir été franchie
            $nextStep = $this->getWorkflow()->getNextStep($agrementStepKey);
            if ($nextStep && $this->getWorkflow()->isStepCrossable($nextStep)) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * 
     * @return TypeAgrement
     */
    protected function getTypeAgrement()
    {
        return $this->getMvcEvent()->getParam('typeAgrement');
    }
    
    /**
     * @return WorkflowIntervenant
     */
    private function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant()
                ->setIntervenant($this->resource->getIntervenant())
                ->setRole($this->role);
        
        return $wf;
    }
    
    /**
     * @return NecessiteAgrementRule
     */
    private function getRuleNecessiteAgrement()
    {
        $rule = $this->getServiceAgrement()->getRuleNecessiteAgrement();
        $rule
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeAgrement($this->resource->getType());
        
        return $rule;
    }
    
    /**
     * @return AgrementFourniRule
     */
    private function getRuleAgrementFourni()
    {
        $rule = $this->getServiceAgrement()->getRuleAgrementFourni();
        $rule
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeAgrement($this->resource->getType());
        
        return $rule;
    }
}