<?php

namespace Application\Service\Traits;

use Application\Service\GroupeTypeFormation;
use Common\Exception\RuntimeException;

trait GroupeTypeFormationAwareTrait
{
    /**
     * description
     *
     * @var GroupeTypeFormation
     */
    private $serviceGroupeTypeFormation;

    /**
     *
     * @param GroupeTypeFormation $serviceGroupeTypeFormation
     * @return self
     */
    public function setServiceGroupeTypeFormation( GroupeTypeFormation $serviceGroupeTypeFormation )
    {
        $this->serviceGroupeTypeFormation = $serviceGroupeTypeFormation;
        return $this;
    }

    /**
     *
     * @return GroupeTypeFormation
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceGroupeTypeFormation()
    {
        if (empty($this->serviceGroupeTypeFormation)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationGroupeTypeFormation');
        }else{
            return $this->serviceGroupeTypeFormation;
        }
    }

}