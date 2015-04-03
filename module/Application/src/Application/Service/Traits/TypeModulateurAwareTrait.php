<?php

namespace Application\Service\Traits;

use Application\Service\TypeModulateur;
use Common\Exception\RuntimeException;

trait TypeModulateurAwareTrait
{
    /**
     * description
     *
     * @var TypeModulateur
     */
    private $serviceTypeModulateur;

    /**
     *
     * @param TypeModulateur $serviceTypeModulateur
     * @return self
     */
    public function setServiceTypeModulateur( TypeModulateur $serviceTypeModulateur )
    {
        $this->serviceTypeModulateur = $serviceTypeModulateur;
        return $this;
    }

    /**
     *
     * @return TypeModulateur
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeModulateur()
    {
        if (empty($this->serviceTypeModulateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeModulateur');
        }else{
            return $this->serviceTypeModulateur;
        }
    }

}