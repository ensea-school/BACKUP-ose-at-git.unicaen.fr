<?php

namespace Application\Service\Traits;

use Application\Service\CheminPedagogique;
use Common\Exception\RuntimeException;

trait CheminPedagogiqueAwareTrait
{
    /**
     * description
     *
     * @var CheminPedagogique
     */
    private $serviceCheminPedagogique;

    /**
     *
     * @param CheminPedagogique $serviceCheminPedagogique
     * @return self
     */
    public function setServiceCheminPedagogique( CheminPedagogique $serviceCheminPedagogique )
    {
        $this->serviceCheminPedagogique = $serviceCheminPedagogique;
        return $this;
    }

    /**
     *
     * @return CheminPedagogique
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceCheminPedagogique()
    {
        if (empty($this->serviceCheminPedagogique)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationCheminPedagogique');
        }else{
            return $this->serviceCheminPedagogique;
        }
    }

}