<?php

namespace Application\Service\Traits;

use Application\Service\NiveauEtape;
use Common\Exception\RuntimeException;

trait NiveauEtapeAwareTrait
{
    /**
     * description
     *
     * @var NiveauEtape
     */
    private $serviceNiveauEtape;

    /**
     *
     * @param NiveauEtape $serviceNiveauEtape
     * @return self
     */
    public function setServiceNiveauEtape( NiveauEtape $serviceNiveauEtape )
    {
        $this->serviceNiveauEtape = $serviceNiveauEtape;
        return $this;
    }

    /**
     *
     * @return NiveauEtape
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceNiveauEtape()
    {
        if (empty($this->serviceNiveauEtape)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationNiveauEtape');
        }else{
            return $this->serviceNiveauEtape;
        }
    }

}