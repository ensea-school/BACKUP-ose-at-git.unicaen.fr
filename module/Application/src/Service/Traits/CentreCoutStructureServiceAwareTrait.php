<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutStructureService;

/**
 * Description of CentreCoutStructureServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutStructureServiceAwareTrait
{
    protected ?CentreCoutStructureService $serviceCentreCoutStructure;



    /**
     * @param CentreCoutStructureService|null $serviceCentreCoutStructure
     *
     * @return self
     */
    public function setServiceCentreCoutStructure( ?CentreCoutStructureService $serviceCentreCoutStructure )
    {
        $this->serviceCentreCoutStructure = $serviceCentreCoutStructure;

        return $this;
    }



    public function getServiceCentreCoutStructure(): ?CentreCoutStructureService
    {
        if (!$this->serviceCentreCoutStructure){
            $this->serviceCentreCoutStructure = \Application::$container->get(CentreCoutStructureService::class);
        }

        return $this->serviceCentreCoutStructure;
    }
}