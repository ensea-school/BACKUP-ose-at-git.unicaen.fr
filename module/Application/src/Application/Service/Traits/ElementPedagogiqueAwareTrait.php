<?php

namespace Application\Service\Traits;

use Application\Service\ElementPedagogique;
use Common\Exception\RuntimeException;

trait ElementPedagogiqueAwareTrait
{
    /**
     * description
     *
     * @var ElementPedagogique
     */
    private $serviceElementPedagogique;

    /**
     *
     * @param ElementPedagogique $serviceElementPedagogique
     * @return self
     */
    public function setServiceElementPedagogique( ElementPedagogique $serviceElementPedagogique )
    {
        $this->serviceElementPedagogique = $serviceElementPedagogique;
        return $this;
    }

    /**
     *
     * @return ElementPedagogique
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceElementPedagogique()
    {
        if (empty($this->serviceElementPedagogique)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationElementPedagogique');
        }else{
            return $this->serviceElementPedagogique;
        }
    }

}