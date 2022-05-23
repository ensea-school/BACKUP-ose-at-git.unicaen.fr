<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateurStructureService;

/**
 * Description of TypeModulateurStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeModulateurStructureServiceAwareTrait
{
    protected ?TypeModulateurStructureService $serviceTypeModulateurStructure = null;



    /**
     * @param TypeModulateurStructureService $serviceTypeModulateurStructure
     *
     * @return self
     */
    public function setServiceTypeModulateurStructure(?TypeModulateurStructureService $serviceTypeModulateurStructure)
    {
        $this->serviceTypeModulateurStructure = $serviceTypeModulateurStructure;

        return $this;
    }



    public function getServiceTypeModulateurStructure(): ?TypeModulateurStructureService
    {
        if (empty($this->serviceTypeModulateurStructure)) {
            $this->serviceTypeModulateurStructure = \Application::$container->get(TypeModulateurStructureService::class);
        }

        return $this->serviceTypeModulateurStructure;
    }
}