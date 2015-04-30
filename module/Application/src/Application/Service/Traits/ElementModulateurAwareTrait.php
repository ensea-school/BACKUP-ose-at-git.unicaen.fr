<?php

namespace Application\Service\Traits;

use Application\Service\ElementModulateur;
use Common\Exception\RuntimeException;

trait ElementModulateurAwareTrait
{
    /**
     * description
     *
     * @var ElementModulateur
     */
    private $serviceElementModulateur;

    /**
     *
     * @param ElementModulateur $serviceElementModulateur
     * @return self
     */
    public function setServiceElementModulateur( ElementModulateur $serviceElementModulateur )
    {
        $this->serviceElementModulateur = $serviceElementModulateur;
        return $this;
    }

    /**
     *
     * @return ElementModulateur
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceElementModulateur()
    {
        if (empty($this->serviceElementModulateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationElementModulateur');
        }else{
            return $this->serviceElementModulateur;
        }
    }

}