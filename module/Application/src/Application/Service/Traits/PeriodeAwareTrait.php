<?php

namespace Application\Service\Traits;

use Application\Service\Periode;
use Common\Exception\RuntimeException;

trait PeriodeAwareTrait
{
    /**
     * description
     *
     * @var Periode
     */
    private $servicePeriode;

    /**
     *
     * @param Periode $servicePeriode
     * @return self
     */
    public function setServicePeriode( Periode $servicePeriode )
    {
        $this->servicePeriode = $servicePeriode;
        return $this;
    }

    /**
     *
     * @return Periode
     * @throws \Common\Exception\RuntimeException
     */
    public function getServicePeriode()
    {
        if (empty($this->servicePeriode)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationPeriode');
        }else{
            return $this->servicePeriode;
        }
    }

}