<?php

namespace Application\Service\Traits;

use Application\Service\CentreCout;
use Common\Exception\RuntimeException;

trait CentreCoutAwareTrait
{
    /**
     * description
     *
     * @var CentreCout
     */
    private $serviceCentreCout;

    /**
     *
     * @param CentreCout $serviceCentreCout
     * @return self
     */
    public function setServiceCentreCout( CentreCout $serviceCentreCout )
    {
        $this->serviceCentreCout = $serviceCentreCout;
        return $this;
    }

    /**
     *
     * @return CentreCout
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceCentreCout()
    {
        if (empty($this->serviceCentreCout)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationCentreCout');
        }else{
            return $this->serviceCentreCout;
        }
    }

}