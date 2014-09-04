<?php

namespace Application\Service\Workflow;

use Application\Service\Workflow\WorkflowIntervenant;

/**
 * Description of WorkflowIntervenantAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
trait WorkflowIntervenantAwareTrait
{
    /**
     * @var WorkflowIntervenant
     */
    protected $workflowIntervenant;
    
    /**
     * Retourne le workflow IntervenantPermanent ou IntervenantExterieur.
     * 
     * @return WorkflowIntervenant
     */
    public function getWorkflowIntervenant()
    {
//        if (null === $this->workflowIntervenant) {
//            if (null === $serviceLocator && method_exists($this, 'getServiceLocator')) {
//                $serviceLocator = $this->getServiceLocator();
//            }
//            if (null === $serviceLocator || !$serviceLocator instanceof ServiceLocatorInterface) {
//                throw new \Common\Exception\LogicException("Aucun service locator valide disponible pour obtenir le workflow.");
//            }
//            $this->workflowIntervenant = $serviceLocator->get("ApplicationWorkflowIntervenant");
//        }
//        
//        $this->workflowIntervenant->setIntervenant($intervenant);
//        
        return $this->workflowIntervenant;
    }
    
    /**
     * 
     * @param WorkflowIntervenant $workflow
     * @return self
     */
    public function setWorkflowIntervenant(WorkflowIntervenant $workflow)
    {
        $this->workflowIntervenant = $workflow;
        return $this;
    }


}