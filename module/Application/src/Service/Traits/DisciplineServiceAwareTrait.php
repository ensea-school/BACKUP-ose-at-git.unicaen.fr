<?php

namespace Application\Service\Traits;

use Application\Service\DisciplineService;

/**
 * Description of DisciplineServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait DisciplineServiceAwareTrait
{
    protected ?DisciplineService $serviceDiscipline = null;



    /**
     * @param DisciplineService $serviceDiscipline
     *
     * @return self
     */
    public function setServiceDiscipline( ?DisciplineService $serviceDiscipline )
    {
        $this->serviceDiscipline = $serviceDiscipline;

        return $this;
    }



    public function getServiceDiscipline(): ?DisciplineService
    {
        if (empty($this->serviceDiscipline)){
            $this->serviceDiscipline = \Application::$container->get(DisciplineService::class);
        }

        return $this->serviceDiscipline;
    }
}