<?php

namespace Application\Service\Assertion;

use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\Agrement;
use Application\Rule\Intervenant\AgrementFourniRule;
use Application\Rule\Intervenant\NecessiteAgrementRule;
use Application\Service\Initializer\AgrementServiceAwareInterface;
use Application\Service\Initializer\AgrementServiceAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;

/**
 * Description of Agrement
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class AgrementAssertion extends EntityAssertion implements AgrementServiceAwareInterface, WorkflowIntervenantAwareInterface
{
    use AgrementServiceAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var Agrement
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
        if (!$resource instanceof Agrement) {
            return false;
        }
        if (!parent::assert($acl, $role, $resource, $privilege)) {
            return false;
        }
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->identityRole instanceof ComposanteDbRole) {
            // structure de responsabilité de l'utilisateur et structure de l'agrément doivent correspondre
            if ($this->identityRole->getStructure() !== $this->resource->getStructure()) {
//            if ($this->resource->getStructure() && $this->identityRole->getStructure()->getId() !== $this->resource->getStructure()->getId()) {
                return false;
            }
        }
        
        $agrementStepKey = 'KEY_' . $this->resource->getType()->getCode();

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

        /*********************************************************
         *                      Rôle X
         *********************************************************/
//        if ($this->identityRole->getRoleId() === \Application\Provider\Role\RoleProvider::ROLE_ID_ADMIN) {
//            
//        }
        
        return false;
    }
    
    /**
     * @return \Application\Service\Workflow\WorkflowIntervenant
     */
    private function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant()
                ->setIntervenant($this->resource->getIntervenant())
                ->setRole($this->identityRole);
        
        return $wf;
    }
    
    /**
     * @return NecessiteAgrementRule
     */
    private function getRuleNecessiteAgrement()
    {
        $rule = $this->getAgrementService()->getRuleNecessiteAgrement();
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
        $rule = $this->getAgrementService()->getRuleAgrementFourni();
        $rule
                ->setIntervenant($this->resource->getIntervenant())
                ->setTypeAgrement($this->resource->getType());
        
        return $rule;
    }
}