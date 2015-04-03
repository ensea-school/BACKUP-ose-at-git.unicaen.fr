<?php

namespace Application\Service\Traits;

use Application\Service\TypeStructure;
use Common\Exception\RuntimeException;

trait TypeStructureAwareTrait
{
    /**
     * description
     *
     * @var TypeStructure
     */
    private $serviceTypeStructure;

    /**
     *
     * @param TypeStructure $serviceTypeStructure
     * @return self
     */
    public function setServiceTypeStructure( TypeStructure $serviceTypeStructure )
    {
        $this->serviceTypeStructure = $serviceTypeStructure;
        return $this;
    }

    /**
     *
     * @return TypeStructure
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeStructure()
    {
        if (empty($this->serviceTypeStructure)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeStructure');
        }else{
            return $this->serviceTypeStructure;
        }
    }

}