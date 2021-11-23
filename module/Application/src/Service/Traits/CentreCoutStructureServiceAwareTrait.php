<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutStructureService;

/**
 * Description of ParametresAwareTrait
 *
 * @author UnicaenCode
 */
trait CentreCoutStructureServiceAwareTrait
{
    /**
     * @var ParametresService
     */
    private $serviceCentreCoutStructure;



    /**
     * @param CentreCoutStructureService $serviceCentreCoutStructure
     *
     * @return self
     */
    public function setServiceCentreCoutStructure(CentreCoutStructureService $serviceCentreCoutStructure)
    {
        $this->serviceCentreCoutStructure = $serviceCentreCoutStructure;

        return $this;
    }



    /**
     * @return CentreCoutStructureService
     */
    public function getServiceCentreCoutStructure()
    {
        if (empty($this->serviceCentreCoutStructure)) {
            $this->serviceCentreCoutStructure = \Application::$container->get(CentreCoutStructureService::class);
        }

        return $this->serviceCentreCoutStructure;
    }
}