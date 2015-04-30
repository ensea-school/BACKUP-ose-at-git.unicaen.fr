<?php

namespace Application\Service\Traits;

use Application\Service\ServiceAPayer;
use Common\Exception\RuntimeException;

trait ServiceAPayerAwareTrait
{
    /**
     * description
     *
     * @var ServiceAPayer
     */
    private $serviceServiceAPayer;

    /**
     *
     * @param ServiceAPayer $serviceServiceAPayer
     * @return self
     */
    public function setServiceServiceAPayer( ServiceAPayer $serviceServiceAPayer )
    {
        $this->serviceServiceAPayer = $serviceServiceAPayer;
        return $this;
    }

    /**
     *
     * @return ServiceAPayer
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceServiceAPayer()
    {
        if (empty($this->serviceServiceAPayer)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationServiceAPayer');
        }else{
            return $this->serviceServiceAPayer;
        }
    }

}