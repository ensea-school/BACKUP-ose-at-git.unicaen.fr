<?php

namespace Application\Service\Traits;

use Application\Service\Periode;

/**
 * Description of PeriodeAwareTrait
 *
 * @author UnicaenCode
 */
trait PeriodeAwareTrait
{
    /**
     * @var Periode
     */
    private $servicePeriode;



    /**
     * @param Periode $servicePeriode
     *
     * @return self
     */
    public function setServicePeriode(Periode $servicePeriode)
    {
        $this->servicePeriode = $servicePeriode;

        return $this;
    }



    /**
     * @return Periode
     */
    public function getServicePeriode()
    {
        if (empty($this->servicePeriode)) {
            $this->servicePeriode = \Application::$container->get('ApplicationPeriode');
        }

        return $this->servicePeriode;
    }
}