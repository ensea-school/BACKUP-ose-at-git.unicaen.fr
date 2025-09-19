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
    protected ?PeriodeService $servicePeriode = null;



    /**
     * @param PeriodeService $servicePeriode
     *
     * @return self
     */
    public function setServicePeriode(?PeriodeService $servicePeriode)
    {
        $this->servicePeriode = $servicePeriode;

        return $this;
    }



    public function getServicePeriode(): ?PeriodeService
    {
        if (empty($this->servicePeriode)) {
            $this->servicePeriode = \Framework\Application\Application::getInstance()->container()->get(PeriodeService::class);
        }

        return $this->servicePeriode;
    }
}