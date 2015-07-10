<?php

namespace Application\Service\Traits;

use Application\Service\CentreCoutEp;
use Common\Exception\RuntimeException;

trait CentreCoutEpAwareTrait
{
    /**
     * description
     *
     * @var CentreCoutEp
     */
    private $serviceCentreCoutEp;

    /**
     *
     * @param CentreCoutEp $serviceCentreCoutEp
     * @return self
     */
    public function setServiceCentreCoutEp( CentreCoutEp $serviceCentreCoutEp )
    {
        $this->serviceCentreCoutEp = $serviceCentreCoutEp;
        return $this;
    }

    /**
     *
     * @return CentreCoutEp
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceCentreCoutEp()
    {
        if (empty($this->serviceCentreCoutEp)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationCentreCoutEp');
        }else{
            return $this->serviceCentreCoutEp;
        }
    }

}