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