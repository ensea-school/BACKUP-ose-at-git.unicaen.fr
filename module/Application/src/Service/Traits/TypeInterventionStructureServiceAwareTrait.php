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
    /**
     * @var TypeInterventionStructureService
     */
    private $serviceTypeInterventionStructure;



    /**
     * @param TypeInterventionStructureService $serviceTypeInterventionStructure
     *
     * @return self
     */
    public function setServiceTypeInterventionStructure(TypeInterventionStructureService $serviceTypeInterventionStructure)
    {
        $this->serviceTypeInterventionStructure = $serviceTypeInterventionStructure;

        return $this;
    }



    /**
     * @return TypeInterventionStructureService
     */
    public function getServiceTypeInterventionStructure()
    {
        if (empty($this->serviceTypeInterventionStructure)) {
            $this->serviceTypeInterventionStructure = \Application::$container->get(TypeInterventionStructureService::class);
        }

        return $this->serviceTypeInterventionStructure;
    }
}