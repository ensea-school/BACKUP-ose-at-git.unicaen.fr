<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\TypeInterventionService;

/**
 * Description of TypeInterventionServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionServiceAwareTrait
{
    protected ?TypeInterventionService $serviceTypeIntervention = null;



    /**
     * @param TypeInterventionService $serviceTypeIntervention
     *
     * @return self
     */
    public function setServiceTypeIntervention(?TypeInterventionService $serviceTypeIntervention)
    {
        $this->serviceTypeIntervention = $serviceTypeIntervention;

        return $this;
    }



    public function getServiceTypeIntervention(): ?TypeInterventionService
    {
        if (empty($this->serviceTypeIntervention)) {
            $this->serviceTypeIntervention = \OseAdmin::instance()->container()->get(TypeInterventionService::class);
        }

        return $this->serviceTypeIntervention;
    }
}