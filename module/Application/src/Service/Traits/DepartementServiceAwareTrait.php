<?php

namespace Application\Service\Traits;

use Application\Service\DepartementService;

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
            $this->serviceDepartement = \Application::$container->get(DepartementService::class);
        }

        return $this->serviceDepartement;
    }
}