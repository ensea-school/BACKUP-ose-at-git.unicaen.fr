<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervenant;
use Common\Exception\RuntimeException;

trait TypeIntervenantAwareTrait
{
    /**
     * description
     *
     * @var TypeIntervenant
     */
    private $serviceTypeIntervenant;

    /**
     *
     * @param TypeIntervenant $serviceTypeIntervenant
     * @return self
     */
    public function setServiceTypeIntervenant( TypeIntervenant $serviceTypeIntervenant )
    {
        $this->serviceTypeIntervenant = $serviceTypeIntervenant;
        return $this;
    }

    /**
     *
     * @return TypeIntervenant
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeIntervenant()
    {
        if (empty($this->serviceTypeIntervenant)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeIntervenant');
        }else{
            return $this->serviceTypeIntervenant;
        }
    }

}