<?php

namespace Application\Service\Traits;

use Application\Service\PeriodeService;

/**
 * Description of PeriodeAwareTrait
 *
 * @author UnicaenCode
 */
trait PeriodeServiceAwareTrait
{
    /**
     * @var PeriodeService
     */
    private $servicePeriode;



    /**
     * @param PeriodeService $servicePeriode
     *
     * @return self
     */
    public function setServicePeriode(PeriodeService $servicePeriode)
    {
        $this->servicePeriode = $servicePeriode;

        return $this;
    }



    /**
     * @return PeriodeService
     */
    public function getServicePeriode()
    {
        if (empty($this->servicePeriode)) {
            $this->servicePeriode = \Application::$container->get(PeriodeService::class);
        }

        return $this->servicePeriode;
    }
}