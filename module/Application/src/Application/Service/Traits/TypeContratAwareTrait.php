<?php

namespace Application\Service\Traits;

use Application\Service\TypeContrat;
use Common\Exception\RuntimeException;

trait TypeContratAwareTrait
{
    /**
     * description
     *
     * @var TypeContrat
     */
    private $serviceTypeContrat;

    /**
     *
     * @param TypeContrat $serviceTypeContrat
     * @return self
     */
    public function setServiceTypeContrat( TypeContrat $serviceTypeContrat )
    {
        $this->serviceTypeContrat = $serviceTypeContrat;
        return $this;
    }

    /**
     *
     * @return TypeContrat
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeContrat()
    {
        if (empty($this->serviceTypeContrat)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeContrat');
        }else{
            return $this->serviceTypeContrat;
        }
    }

}