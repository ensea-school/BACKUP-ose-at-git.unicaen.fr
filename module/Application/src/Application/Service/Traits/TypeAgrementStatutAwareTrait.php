<?php

namespace Application\Service\Traits;

use Application\Service\TypeAgrementStatut;
use Common\Exception\RuntimeException;

trait TypeAgrementStatutAwareTrait
{
    /**
     * description
     *
     * @var TypeAgrementStatut
     */
    private $serviceTypeAgrementStatut;

    /**
     *
     * @param TypeAgrementStatut $serviceTypeAgrementStatut
     * @return self
     */
    public function setServiceTypeAgrementStatut( TypeAgrementStatut $serviceTypeAgrementStatut )
    {
        $this->serviceTypeAgrementStatut = $serviceTypeAgrementStatut;
        return $this;
    }

    /**
     *
     * @return TypeAgrementStatut
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeAgrementStatut()
    {
        if (empty($this->serviceTypeAgrementStatut)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeAgrementStatut');
        }else{
            return $this->serviceTypeAgrementStatut;
        }
    }

}