<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrement;
use Common\Exception\RuntimeException;

trait TypeAgrementAwareTrait
{
    /**
     * description
     *
     * @var TypeAgrement
     */
    private $serviceTypeAgrement;

    /**
     *
     * @param TypeAgrement $serviceTypeAgrement
     * @return self
     */
    public function setServiceTypeAgrement( TypeAgrement $serviceTypeAgrement )
    {
        $this->serviceTypeAgrement = $serviceTypeAgrement;
        return $this;
    }

    /**
     *
     * @return TypeAgrement
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeAgrement()
    {
        if (empty($this->serviceTypeAgrement)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeAgrement');
        }else{
            return $this->serviceTypeAgrement;
        }
    }

}