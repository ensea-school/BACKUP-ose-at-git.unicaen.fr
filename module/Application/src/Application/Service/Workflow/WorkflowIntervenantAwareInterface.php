<?php

namespace Application\Service\Workflow;

use Application\Service\Workflow\WorkflowIntervenant;

/**
 * Description of WorkflowIntervenantAwareTrait
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
interface WorkflowIntervenantAwareInterface
{
    /**
     * Retourne le workflow concernant un intervenant.
     * 
     * @return WorkflowIntervenant
     */
    public function getWorkflowIntervenant();
    
    /**
     * Fournit le workflow concernant un intervenant.
     * 
     * @param WorkflowIntervenant $workflow
     * @return self
     */
    public function setWorkflowIntervenant(WorkflowIntervenant $workflow);
}