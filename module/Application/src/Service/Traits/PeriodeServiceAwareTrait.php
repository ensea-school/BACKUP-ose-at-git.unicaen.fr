<?php

namespace Application\Service\Traits;

use Application\Service\PeriodeService;

/**
 * Description of PeriodeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PeriodeServiceAwareTrait
{
    protected ?PeriodeService $servicePeriode;



    /**
     * @param PeriodeService|null $servicePeriode
     *
     * @return self
     */
    public function setServicePeriode( ?PeriodeService $servicePeriode )
    {
        $this->servicePeriode = $servicePeriode;

        return $this;
    }



    public function getServicePeriode(): ?PeriodeService
    {
        if (!$this->servicePeriode){
            $this->servicePeriode = \Application::$container->get(PeriodeService::class);
        }

        return $this->servicePeriode;
    }
}