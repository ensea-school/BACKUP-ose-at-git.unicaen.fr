<?php

namespace Application\Service\Traits;

use Application\Service\Modulateur;
use Common\Exception\RuntimeException;

trait ModulateurAwareTrait
{
    /**
     * description
     *
     * @var Modulateur
     */
    private $serviceModulateur;

    /**
     *
     * @param Modulateur $serviceModulateur
     * @return self
     */
    public function setServiceModulateur( Modulateur $serviceModulateur )
    {
        $this->serviceModulateur = $serviceModulateur;
        return $this;
    }

    /**
     *
     * @return Modulateur
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceModulateur()
    {
        if (empty($this->serviceModulateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationModulateur');
        }else{
            return $this->serviceModulateur;
        }
    }

}