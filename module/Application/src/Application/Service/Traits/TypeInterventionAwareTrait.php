<?php

namespace Application\Service\Traits;

use Application\Service\TypeIntervention;
use Common\Exception\RuntimeException;

trait TypeInterventionAwareTrait
{
    /**
     * description
     *
     * @var TypeIntervention
     */
    private $serviceTypeIntervention;

    /**
     *
     * @param TypeIntervention $serviceTypeIntervention
     * @return self
     */
    public function setServiceTypeIntervention( TypeIntervention $serviceTypeIntervention )
    {
        $this->serviceTypeIntervention = $serviceTypeIntervention;
        return $this;
    }

    /**
     *
     * @return TypeIntervention
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceTypeIntervention()
    {
        if (empty($this->serviceTypeIntervention)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationTypeIntervention');
        }else{
            return $this->serviceTypeIntervention;
        }
    }

}