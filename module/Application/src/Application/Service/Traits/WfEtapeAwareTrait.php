<?php

namespace Application\Service\Traits;

use Application\Service\WfEtape;
use Common\Exception\RuntimeException;

trait WfEtapeAwareTrait
{
    /**
     * description
     *
     * @var WfEtape
     */
    private $serviceWfEtape;

    /**
     *
     * @param WfEtape $serviceWfEtape
     * @return self
     */
    public function setServiceWfEtape( WfEtape $serviceWfEtape )
    {
        $this->serviceWfEtape = $serviceWfEtape;
        return $this;
    }

    /**
     *
     * @return WfEtape
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceWfEtape()
    {
        if (empty($this->serviceWfEtape)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationWfEtape');
        }else{
            return $this->serviceWfEtape;
        }
    }

}