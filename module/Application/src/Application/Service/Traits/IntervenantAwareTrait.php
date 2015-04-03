<?php

namespace Application\Service\Traits;

use Application\Service\Intervenant;
use Common\Exception\RuntimeException;

trait IntervenantAwareTrait
{
    /**
     * description
     *
     * @var Intervenant
     */
    private $serviceIntervenant;

    /**
     *
     * @param Intervenant $serviceIntervenant
     * @return self
     */
    public function setServiceIntervenant( Intervenant $serviceIntervenant )
    {
        $this->serviceIntervenant = $serviceIntervenant;
        return $this;
    }

    /**
     *
     * @return Intervenant
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceIntervenant()
    {
        if (empty($this->serviceIntervenant)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationIntervenant');
        }else{
            return $this->serviceIntervenant;
        }
    }

}