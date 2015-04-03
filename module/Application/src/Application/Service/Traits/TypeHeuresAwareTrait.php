<?php

namespace Application\Service\Traits;

use Application\Service\TypeHeures;
use Common\Exception\RuntimeException;

trait TypeHeuresAwareTrait
{
    /**
     * description
     *
     * @var TypeHeures
     */
    private $serviceTypeHeures;

    /**
     *
     * @param TypeHeures $serviceTypeHeures
     * @return self
     */
    public function setServiceTypeHeures( TypeHeures $serviceTypeHeures )
    {
        $this->serviceTypeHeures = $serviceTypeHeures;
        return $this;
    }

    /**
     *
     * @return TypeHeures
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeHeures()
    {
        if (empty($this->serviceTypeHeures)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeHeures');
        }else{
            return $this->serviceTypeHeures;
        }
    }

}