<?php

namespace Application\Service\Traits;

use Application\Service\Indicateur;
use Common\Exception\RuntimeException;

trait IndicateurAwareTrait
{
    /**
     * description
     *
     * @var Indicateur
     */
    private $serviceIndicateur;

    /**
     *
     * @param Indicateur $serviceIndicateur
     * @return self
     */
    public function setServiceIndicateur( Indicateur $serviceIndicateur )
    {
        $this->serviceIndicateur = $serviceIndicateur;
        return $this;
    }

    /**
     *
     * @return Indicateur
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceIndicateur()
    {
        if (empty($this->serviceIndicateur)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationIndicateur');
        }else{
            return $this->serviceIndicateur;
        }
    }

}