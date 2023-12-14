<?php

namespace Lieu\Service;

/**
 * Description of DepartementServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementServiceAwareTrait
{
    protected ?DepartementService $serviceDepartement = null;



    /**
     * @param DepartementService $serviceDepartement
     *
     * @return self
     */
    public function setServiceDepartement(?DepartementService $serviceDepartement)
    {
        $this->serviceDepartement = $serviceDepartement;

        return $this;
    }



    public function getServiceDepartement(): ?DepartementService
    {
        if (empty($this->serviceDepartement)) {
            $this->serviceDepartement = \OseAdmin::instance()->container()->get(DepartementService::class);
        }

        return $this->serviceDepartement;
    }
}