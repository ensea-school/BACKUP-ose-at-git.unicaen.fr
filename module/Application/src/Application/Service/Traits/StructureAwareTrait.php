<?php

namespace Application\Service\Traits;

use Application\Service\Structure;
use Common\Exception\RuntimeException;

trait StructureAwareTrait
{
    /**
     * description
     *
     * @var Structure
     */
    private $serviceStructure;

    /**
     *
     * @param Structure $serviceStructure
     * @return self
     */
    public function setServiceStructure( Structure $serviceStructure )
    {
        $this->serviceStructure = $serviceStructure;
        return $this;
    }

    /**
     *
     * @return Structure
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceStructure()
    {
        if (empty($this->serviceStructure)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationStructure');
        }else{
            return $this->serviceStructure;
        }
    }

}