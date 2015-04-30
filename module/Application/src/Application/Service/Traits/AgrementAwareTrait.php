<?php

namespace Application\Service\Traits;

use Application\Service\Agrement;
use Common\Exception\RuntimeException;

trait AgrementAwareTrait
{
    /**
     * description
     *
     * @var Agrement
     */
    private $serviceAgrement;

    /**
     *
     * @param Agrement $serviceAgrement
     * @return self
     */
    public function setServiceAgrement( Agrement $serviceAgrement )
    {
        $this->serviceAgrement = $serviceAgrement;
        return $this;
    }

    /**
     *
     * @return Agrement
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceAgrement()
    {
        if (empty($this->serviceAgrement)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationAgrement');
        }else{
            return $this->serviceAgrement;
        }
    }

}