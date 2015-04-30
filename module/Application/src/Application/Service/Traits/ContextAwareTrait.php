<?php

namespace Application\Service\Traits;

use Application\Service\Context;
use Common\Exception\RuntimeException;

trait ContextAwareTrait
{
    /**
     * description
     *
     * @var Context
     */
    private $serviceContext;

    /**
     *
     * @param Context $serviceContext
     * @return self
     */
    public function setServiceContext( Context $serviceContext )
    {
        $this->serviceContext = $serviceContext;
        return $this;
    }

    /**
     *
     * @return Context
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceContext()
    {
        if (empty($this->serviceContext)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationContext');
        }else{
            return $this->serviceContext;
        }
    }

}