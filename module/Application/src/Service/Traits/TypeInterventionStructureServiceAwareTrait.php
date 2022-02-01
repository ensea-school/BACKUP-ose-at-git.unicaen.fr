<?php

namespace Application\Service\Traits;

use Application\Service\TypeInterventionStructureService;

/**
 * Description of TypeInterventionStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureServiceAwareTrait
{
    protected ?TypeInterventionStructureService $serviceTypeInterventionStructure;



    /**
     * @param TypeInterventionStructureService|null $serviceTypeInterventionStructure
     *
     * @return self
     */
    public function setServiceTypeInterventionStructure( ?TypeInterventionStructureService $serviceTypeInterventionStructure )
    {
        $this->serviceTypeInterventionStructure = $serviceTypeInterventionStructure;

        return $this;
    }



    public function getServiceTypeInterventionStructure(): ?TypeInterventionStructureService
    {
        if (!$this->serviceTypeInterventionStructure){
            $this->serviceTypeInterventionStructure = \Application::$container->get(TypeInterventionStructureService::class);
        }

        return $this->serviceTypeInterventionStructure;
    }
}