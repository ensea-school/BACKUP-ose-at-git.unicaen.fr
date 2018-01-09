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
    /**
     * @var TypeModulateurStructureService
     */
    private $serviceTypeModulateurStructure;



    /**
     * @param TypeModulateurStructureService $serviceTypeModulateurStructure
     *
     * @return self
     */
    public function setServiceTypeModulateurStructure(TypeModulateurStructureService $serviceTypeModulateurStructure)
    {
        $this->serviceTypeModulateurStructure = $serviceTypeModulateurStructure;

        return $this;
    }



    /**
     * @return TypeModulateurStructureService
     */
    public function getServiceTypeModulateurStructure()
    {
        if (empty($this->serviceTypeModulateurStructure)) {
            $this->serviceTypeModulateurStructure = \Application::$container->get(TypeModulateurStructureService::class);
        }

        return $this->serviceTypeModulateurStructure;
    }
}