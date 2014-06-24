<?php

namespace Application\Service\Workflow;

use Application\Entity\Db\TypeValidation;
use Application\Traits\IntervenantAwareTrait;
use Application\Service\Workflow\Step\Step;

/**
 * Implémentation du workflow concernant un intervenant.
 *
 * @author Bertrand GAUTHIER <bertrand.gauthier at unicaen.fr>
 */
abstract class WorkflowIntervenant extends AbstractWorkflow
{
    use IntervenantAwareTrait;
    
    /**
     * 
     * @param \Application\Service\Workflow\Step $step
     * @return string
     */
    public function getStepUrl(Step $step)
    {
        $url = $this->getHelperUrl()->fromRoute($step->getRoute(), array('id' => $this->getIntervenant()->getSourceCode()));
        
        return $url;
    }
    
    /**
     * 
     * @return \Application\Service\TypeValidation
     */
    protected function getServiceTypeValidation()
    {
        return $this->getServiceLocator()->get('ApplicationTypeValidation');
    } 
    
    /**
     * @return TypeValidation
     */
    protected function getTypeValidationService()
    {
        $qb = $this->getServiceTypeValidation()->finderByCode(TypeValidation::CODE_SERVICES_PAR_COMP);
        $typeValidation = $qb->getQuery()->getOneOrNullResult();
        
        return $typeValidation;
    }
}