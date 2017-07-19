<?php

namespace Application\Service\Interfaces;

use Application\Service\Periode;
use RuntimeException;

/**
 * Description of PeriodeAwareInterface
 *
 * @author UnicaenCode
 */
interface PeriodeAwareInterface
{
    /**
     * @param Periode $servicePeriode
     * @return self
     */
    public function setServicePeriode( Periode $servicePeriode );



    /**
     * @return PeriodeAwareInterface
     * @throws RuntimeException
     */
    public function getServicePeriode();
}