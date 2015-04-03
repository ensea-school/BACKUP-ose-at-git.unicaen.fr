<?php

namespace Application\Service\Traits;

use Application\Service\ContextProvider;
use Common\Exception\RuntimeException;

trait ContextProviderAwareTrait
{
    /**
     * description
     *
     * @var ContextProvider
     */
    private $serviceContextProvider;

    /**
     *
     * @param ContextProvider $serviceContextProvider
     * @return self
     */
    public function setServiceContextProvider( ContextProvider $serviceContextProvider )
    {
        $this->serviceContextProvider = $serviceContextProvider;
        return $this;
    }

    /**
     *
     * @return ContextProvider
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceContextProvider()
    {
        if (empty($this->serviceContextProvider)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationContextProvider');
        }else{
            return $this->serviceContextProvider;
        }
    }

}