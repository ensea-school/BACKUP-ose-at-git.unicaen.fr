<?php

namespace Application\Service\Traits;

use Application\Service\StatutIntervenant;
use Common\Exception\RuntimeException;

trait StatutIntervenantAwareTrait
{
    /**
     * description
     *
     * @var StatutIntervenant
     */
    private $serviceStatutIntervenant;

    /**
     *
     * @param StatutIntervenant $serviceStatutIntervenant
     * @return self
     */
    public function setServiceStatutIntervenant( StatutIntervenant $serviceStatutIntervenant )
    {
        $this->serviceStatutIntervenant = $serviceStatutIntervenant;
        return $this;
    }

    /**
     *
     * @return StatutIntervenant
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceStatutIntervenant()
    {
        if (empty($this->serviceStatutIntervenant)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationStatutIntervenant');
        }else{
            return $this->serviceStatutIntervenant;
        }
    }

}