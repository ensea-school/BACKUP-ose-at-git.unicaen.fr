<?php

namespace Lieu\Service;

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
    public function setServiceStructure(?StructureService $serviceStructure)
    {
        $this->serviceStructure = $serviceStructure;

        return $this;
    }



    public function getServiceStructure(): ?StructureService
    {
        if (empty($this->serviceStructure)) {
            $this->serviceStructure = \Framework\Application\Application::getInstance()->container()->get(StructureService::class);
        }

        return $this->serviceStructure;
    }
}