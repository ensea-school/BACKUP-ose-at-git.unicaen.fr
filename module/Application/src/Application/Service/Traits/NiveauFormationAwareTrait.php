<?php

namespace Application\Service\Traits;

use Application\Service\NiveauFormation;
use Common\Exception\RuntimeException;

trait NiveauFormationAwareTrait
{
    /**
     * description
     *
     * @var NiveauFormation
     */
    private $serviceNiveauFormation;

    /**
     *
     * @param NiveauFormation $serviceNiveauFormation
     * @return self
     */
    public function setServiceNiveauFormation( NiveauFormation $serviceNiveauFormation )
    {
        $this->serviceNiveauFormation = $serviceNiveauFormation;
        return $this;
    }

    /**
     *
     * @return NiveauFormation
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceNiveauFormation()
    {
        if (empty($this->serviceNiveauFormation)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationNiveauFormation');
        }else{
            return $this->serviceNiveauFormation;
        }
    }

}