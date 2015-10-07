<?php

namespace Application\Service\Traits;

use Application\Service\<entityClass>;
use Application\Module;
use Common\Exception\RuntimeException;

trait <entityClass>AwareTrait
{
    /**
     * description
     *
     * @var <entityClass>
     */
    private $service<entityClass>;

    /**
     *
     * @param <entityClass> $service<entityClass>
     * @return self
     */
    public function setService<entityClass>( <entityClass> $service<entityClass> )
    {
        $this->service<entityClass> = $service<entityClass>;
        return $this;
    }

    /**
     *
     * @return <entityClass>
     * @throws \Common\Exception\RuntimeException
     */
    public function getService<entityClass>()
    {
        if (empty($this->service<entityClass>)){
            $serviceLocator = Module::$serviceLocator;
            if (! $serviceLocator){
                if (! method_exists($this, 'getServiceLocator')) {
                    throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
                }

                $serviceLocator = $this->getServiceLocator();
                if (method_exists($serviceLocator, 'getServiceLocator')) {
                    $serviceLocator = $serviceLocator->getServiceLocator();
                }
            }
            return $serviceLocator->get('application<entityClass>');
        }else{
            return $this->service<entityClass>;
        }
    }

}