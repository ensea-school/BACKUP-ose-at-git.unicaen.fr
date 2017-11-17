<?php

namespace Application\Service\Traits;

use Application\Service\Departement;

/**
 * Description of DepartementAwareTrait
 *
 * @author UnicaenCode
 */
trait DepartementAwareTrait
{
    /**
     * @var Departement
     */
    private $serviceDepartement;



    /**
     * @param Departement $serviceDepartement
     *
     * @return self
     */
    public function setServiceDepartement(Departement $serviceDepartement)
    {
        $this->serviceDepartement = $serviceDepartement;

        return $this;
    }



    /**
     * @return Departement
     */
    public function getServiceDepartement()
    {
        if (empty($this->serviceDepartement)) {
            $this->serviceDepartement = \Application::$container->get('ApplicationDepartement');
        }

        return $this->serviceDepartement;
    }
}