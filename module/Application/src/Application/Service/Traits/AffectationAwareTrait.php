<?php

namespace Application\Service\Traits;

use Application\Service\Affectation;
use Common\Exception\RuntimeException;

trait AffectationAwareTrait
{
    /**
     * description
     *
     * @var Affectation
     */
    private $serviceAffectation;

    /**
     *
     * @param Affectation $serviceAffectation
     * @return self
     */
    public function setServiceAffectation( Affectation $serviceAffectation )
    {
        $this->serviceAffectation = $serviceAffectation;
        return $this;
    }

    /**
     *
     * @return Affectation
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceAffectation()
    {
        if (empty($this->serviceAffectation)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationAffectation');
        }else{
            return $this->serviceAffectation;
        }
    }

}