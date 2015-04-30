<?php

namespace Application\Service\Traits;

use Application\Service\Source;
use Common\Exception\RuntimeException;

trait SourceAwareTrait
{
    /**
     * description
     *
     * @var Source
     */
    private $serviceSource;

    /**
     *
     * @param Source $serviceSource
     * @return self
     */
    public function setServiceSource( Source $serviceSource )
    {
        $this->serviceSource = $serviceSource;
        return $this;
    }

    /**
     *
     * @return Source
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceSource()
    {
        if (empty($this->serviceSource)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationSource');
        }else{
            return $this->serviceSource;
        }
    }

}