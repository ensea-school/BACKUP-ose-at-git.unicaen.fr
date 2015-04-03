<?php

namespace Application\Service\Traits;

use Application\Service\Etape;
use Common\Exception\RuntimeException;

trait EtapeAwareTrait
{
    /**
     * description
     *
     * @var Etape
     */
    private $serviceEtape;

    /**
     *
     * @param Etape $serviceEtape
     * @return self
     */
    public function setServiceEtape( Etape $serviceEtape )
    {
        $this->serviceEtape = $serviceEtape;
        return $this;
    }

    /**
     *
     * @return Etape
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceEtape()
    {
        if (empty($this->serviceEtape)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationEtape');
        }else{
            return $this->serviceEtape;
        }
    }

}