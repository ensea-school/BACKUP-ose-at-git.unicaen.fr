<?php

namespace Application\Service\Traits;

use Application\Service\LocalContext;
use Common\Exception\RuntimeException;

trait LocalContextAwareTrait
{
    /**
     * description
     *
     * @var LocalContext
     */
    private $serviceLocalContext;

    /**
     *
     * @param LocalContext $serviceLocalContext
     * @return self
     */
    public function setServiceLocalContext( LocalContext $serviceLocalContext )
    {
        $this->serviceLocalContext = $serviceLocalContext;
        return $this;
    }

    /**
     *
     * @return LocalContext
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceLocalContext()
    {
        if (empty($this->serviceLocalContext)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationLocalContext');
        }else{
            return $this->serviceLocalContext;
        }
    }

}