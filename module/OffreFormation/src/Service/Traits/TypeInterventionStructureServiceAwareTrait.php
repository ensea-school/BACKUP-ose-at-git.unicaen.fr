<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\TypeInterventionStructureService;

/**
 * Description of TypeInterventionStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureServiceAwareTrait
{
    protected ?TypeInterventionStructureService $serviceTypeInterventionStructure = null;



    /**
     * @param TypeInterventionStructureService $serviceTypeInterventionStructure
     *
     * @return self
     */
    public function setServiceTypeInterventionStructure(?TypeInterventionStructureService $serviceTypeInterventionStructure)
    {
        $this->serviceTypeInterventionStructure = $serviceTypeInterventionStructure;

        return $this;
    }



    public function getServiceTypeInterventionStructure(): ?TypeInterventionStructureService
    {
        if (empty($this->serviceTypeInterventionStructure)) {
            $this->serviceTypeInterventionStructure = \Framework\Application\Application::getInstance()->container()->get(TypeInterventionStructureService::class);
        }

        return $this->serviceTypeInterventionStructure;
    }
}