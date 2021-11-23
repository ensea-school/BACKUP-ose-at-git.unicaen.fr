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
    /**
     * @var DisciplineService
     */
    private $serviceDiscipline;



    /**
     * @param DisciplineService $serviceDiscipline
     *
     * @return self
     */
    public function setServiceDiscipline(DisciplineService $serviceDiscipline)
    {
        $this->serviceDiscipline = $serviceDiscipline;

        return $this;
    }



    /**
     * @return DisciplineService
     */
    public function getServiceDiscipline()
    {
        if (empty($this->serviceDiscipline)) {
            $this->serviceDiscipline = \Application::$container->get(DisciplineService::class);
        }

        return $this->serviceDiscipline;
    }
}