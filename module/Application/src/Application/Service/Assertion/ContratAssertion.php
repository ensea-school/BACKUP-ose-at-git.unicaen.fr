<?php

namespace Application\Service\Assertion;

use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Application\Acl\ComposanteDbRole;
use Application\Entity\Db\Contrat;
use Application\Service\Initializer\ContratServiceAwareInterface;
use Application\Service\Initializer\ContratServiceAwareTrait;
use Application\Service\Workflow\WorkflowIntervenant;
use Application\Traits\WorkflowIntervenantAwareTrait;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use Zend\Permissions\Acl\Role\RoleInterface;

/**
 * Description of Contrat
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
class ContratAssertion extends EntityAssertion implements ServiceLocatorAwareInterface//, ContratServiceAwareInterface
{
//    use ContratServiceAwareTrait;
    use ServiceLocatorAwareTrait;
    use WorkflowIntervenantAwareTrait;
    
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
        if (!$resource instanceof Contrat) {
            return false;
        }
        if (!parent::assert($acl, $role, $resource, $privilege)) {
            return false;
        }
        
        /*********************************************************
         *                      Rôle Composante
         *********************************************************/
        if ($this->identityRole instanceof ComposanteDbRole) 
        {
            // structure de responsabilité de l'utilisateur et structure du contrat doivent correspondre
            if ($this->identityRole->getStructure() !== $this->resource->getStructure()) {
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

//        /*********************************************************
//         *                      Rôle X
//         *********************************************************/
//        if ($this->identityRole instanceof XRole) {
//            
//        }
        
        return false;
    }
    
    /**
     * @return WorkflowIntervenant
     */
    private function getWorkflow()
    {
        $wf = $this->getWorkflowIntervenant($this->resource->getIntervenant(), $this->getServiceLocator())
                ->setRole($this->identityRole);
        
        return $wf;
    }
}