<?php

namespace Paiement\Service;


/**
 * Description of CentreCoutStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutStructureServiceAwareTrait
{
    protected ?CentreCoutStructureService $serviceCentreCoutStructure = null;



    /**
     * @param CentreCoutStructureService $serviceCentreCoutStructure
     *
     * @return self
     */
    public function setServiceCentreCoutStructure(?CentreCoutStructureService $serviceCentreCoutStructure)
    {
        $this->serviceCentreCoutStructure = $serviceCentreCoutStructure;

        return $this;
    }



    public function getServiceCentreCoutStructure(): ?CentreCoutStructureService
    {
        if (empty($this->serviceCentreCoutStructure)) {
            $this->serviceCentreCoutStructure = \Framework\Application\Application::getInstance()->container()->get(CentreCoutStructureService::class);
        }

        return $this->serviceCentreCoutStructure;
    }
}