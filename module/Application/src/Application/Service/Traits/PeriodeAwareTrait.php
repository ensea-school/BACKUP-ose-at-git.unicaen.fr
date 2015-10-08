<?php

namespace Application\Service\Traits;

use Application\Service\Periode;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setServicePeriode( Periode $servicePeriode )
    {
        $this->servicePeriode = $servicePeriode;
        return $this;
    }



    /**
     * @return Periode
     * @throws RuntimeException
     */
    public function getServicePeriode()
    {
        if (empty($this->servicePeriode)){
        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        $this->servicePeriode = $serviceLocator->get('ApplicationPeriode');
        }
        return $this->servicePeriode;
    }
}