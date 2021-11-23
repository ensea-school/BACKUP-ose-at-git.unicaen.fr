<?php

namespace Application\Service\Traits;

use Application\Service\StructureService;

/**
 * Description of StructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait StructureServiceAwareTrait
{
    /**
     * @var StructureService
     */
    private $serviceStructure;



    /**
     * @param StructureService $serviceStructure
     *
     * @return self
     */
    public function setServiceStructure(StructureService $serviceStructure)
    {
        $this->serviceStructure = $serviceStructure;

        return $this;
    }



    /**
     * @return StructureService
     */
    public function getServiceStructure()
    {
        if (empty($this->serviceStructure)) {
            $this->serviceStructure = \Application::$container->get(StructureService::class);
        }

        return $this->serviceStructure;
    }
}