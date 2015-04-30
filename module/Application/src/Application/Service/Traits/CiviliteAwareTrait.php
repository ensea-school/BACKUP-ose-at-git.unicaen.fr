<?php

namespace Application\Service\Traits;

use Application\Service\Civilite;
use Common\Exception\RuntimeException;

trait CiviliteAwareTrait
{
    /**
     * description
     *
     * @var Civilite
     */
    private $serviceCivilite;

    /**
     *
     * @param Civilite $serviceCivilite
     * @return self
     */
    public function setServiceCivilite( Civilite $serviceCivilite )
    {
        $this->serviceCivilite = $serviceCivilite;
        return $this;
    }

    /**
     *
     * @return Civilite
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceCivilite()
    {
        if (empty($this->serviceCivilite)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationCivilite');
        }else{
            return $this->serviceCivilite;
        }
    }

}