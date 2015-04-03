<?php

namespace Application\Service\Traits;

use Application\Service\IntervenantNavigationPageVisibility;
use Common\Exception\RuntimeException;

trait IntervenantNavigationPageVisibilityAwareTrait
{
    /**
     * description
     *
     * @var IntervenantNavigationPageVisibility
     */
    private $serviceIntervenantNavigationPageVisibility;

    /**
     *
     * @param IntervenantNavigationPageVisibility $serviceIntervenantNavigationPageVisibility
     * @return self
     */
    public function setServiceIntervenantNavigationPageVisibility( IntervenantNavigationPageVisibility $serviceIntervenantNavigationPageVisibility )
    {
        $this->serviceIntervenantNavigationPageVisibility = $serviceIntervenantNavigationPageVisibility;
        return $this;
    }

    /**
     *
     * @return IntervenantNavigationPageVisibility
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceIntervenantNavigationPageVisibility()
    {
        if (empty($this->serviceIntervenantNavigationPageVisibility)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationIntervenantNavigationPageVisibility');
        }else{
            return $this->serviceIntervenantNavigationPageVisibility;
        }
    }

}