<?php

namespace Application\Service\Assertion;

use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\Contrat;
use Application\Service\Workflow\WorkflowIntervenant;
use Application\Service\Workflow\WorkflowIntervenantAwareInterface;
use Application\Service\Workflow\WorkflowIntervenantAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Contrat
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratAssertion extends AbstractAssertion implements WorkflowIntervenantAwareInterface
{
    use WorkflowIntervenantAwareTrait;
    
    /**
     * @var Contrat
     */
    protected $contrat;
    
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
        if ($resource instanceof Contrat) {
            return $this->assertEntity($acl, $role, $resource, $privilege);
        }
        
        return false;
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
        
        $this->contrat = $resource;
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->getSelectedIdentityRole() instanceof ComposanteDbRole) 
        {
            // structure de responsabilité de l'utilisateur et structure du contrat doivent correspondre
            if ($this->getSelectedIdentityRole()->getStructure() !== $this->contrat->getStructure()) {
                return false;
            }
            
            $contratStepKey = WorkflowIntervenant::KEY_EDITION_CONTRAT;
            
            // l'étape Contrat du workflow doit être atteignable
            if (!$this->getWorkflow()->isStepReachable($contratStepKey)) {
                return false;
            }
            
            // l'étape suivante du workflow ne doit pas avoir été franchie
            $nextStep = $this->getWorkflow()->getNextStep($contratStepKey);
            if ($nextStep && $this->getWorkflow()->isStepCrossable($nextStep)) {
                return false;
            }
            
            return true;
        }
        
        return true;
    }
    
    /**
     * @return WorkflowIntervenant
     */
    private function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant()
                ->setIntervenant($this->contrat->getIntervenant())
                ->setRole($this->getSelectedIdentityRole());
        
        return $wf;
    }
}