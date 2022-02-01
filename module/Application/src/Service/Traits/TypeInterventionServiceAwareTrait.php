<?php

namespace Application\Service\Traits;

use Application\Service\TypeInterventionService;

/**
 * Description of TypeInterventionServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionServiceAwareTrait
{
    protected ?TypeInterventionService $serviceTypeIntervention;



    /**
     * @param TypeInterventionService|null $serviceTypeIntervention
     *
     * @return self
     */
    public function setServiceTypeIntervention( ?TypeInterventionService $serviceTypeIntervention )
    {
        $this->serviceTypeIntervention = $serviceTypeIntervention;

        return $this;
    }



    public function getServiceTypeIntervention(): ?TypeInterventionService
    {
        if (!$this->serviceTypeIntervention){
            $this->serviceTypeIntervention = \Application::$container->get(TypeInterventionService::class);
        }

        return $this->serviceTypeIntervention;
    }
}