<?php

namespace Application\Service\Traits;

use Application\Service\Parametres;
use Common\Exception\RuntimeException;

trait ParametresAwareTrait
{
    /**
     * description
     *
     * @var Parametres
     */
    private $serviceParametres;

    /**
     *
     * @param Parametres $serviceParametres
     * @return self
     */
    public function setServiceParametres( Parametres $serviceParametres )
    {
        $this->serviceParametres = $serviceParametres;
        return $this;
    }

    /**
     *
     * @return Parametres
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceParametres()
    {
        if (empty($this->serviceParametres)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationParametres');
        }else{
            return $this->serviceParametres;
        }
    }

}