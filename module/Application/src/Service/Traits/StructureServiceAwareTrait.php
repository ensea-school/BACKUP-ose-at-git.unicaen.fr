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
    protected ?StructureService $serviceStructure = null;



    /**
     * @param StructureService $serviceStructure
     *
     * @return self
     */
    public function setServiceStructure( ?StructureService $serviceStructure )
    {
        $this->serviceStructure = $serviceStructure;

        return $this;
    }



    public function getServiceStructure(): ?StructureService
    {
        if (empty($this->serviceStructure)){
            $this->serviceStructure = \Application::$container->get(StructureService::class);
        }

        return $this->serviceStructure;
    }
}