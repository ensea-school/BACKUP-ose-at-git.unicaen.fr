<?php

namespace Application\Service\Traits;

use Application\Service\DepartementService;

/**
 * Description of DepartementAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementServiceAwareTrait
{
    /**
     * @var DepartementService
     */
    private $serviceDepartement;



    /**
     * @param DepartementService $serviceDepartement
     *
     * @return self
     */
    public function setServiceDepartement(DepartementService $serviceDepartement)
    {
        $this->serviceDepartement = $serviceDepartement;

        return $this;
    }



    /**
     * @return DepartementService
     */
    public function getServiceDepartement()
    {
        if (empty($this->serviceDepartement)) {
            $this->serviceDepartement = \Application::$container->get(DepartementService::class);
        }

        return $this->serviceDepartement;
    }
}