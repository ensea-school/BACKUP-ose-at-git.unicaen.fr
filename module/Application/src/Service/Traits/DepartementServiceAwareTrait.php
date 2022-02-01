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
    protected ?DepartementService $serviceDepartement;



    /**
     * @param DepartementService|null $serviceDepartement
     *
     * @return self
     */
    public function setServiceDepartement( ?DepartementService $serviceDepartement )
    {
        $this->serviceDepartement = $serviceDepartement;

        return $this;
    }



    public function getServiceDepartement(): ?DepartementService
    {
        if (!$this->serviceDepartement){
            $this->serviceDepartement = \Application::$container->get(DepartementService::class);
        }

        return $this->serviceDepartement;
    }
}