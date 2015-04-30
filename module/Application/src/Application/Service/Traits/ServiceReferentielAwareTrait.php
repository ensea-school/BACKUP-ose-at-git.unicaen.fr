<?php

namespace Application\Service\Traits;

use Application\Service\ServiceReferentiel;
use Common\Exception\RuntimeException;

trait ServiceReferentielAwareTrait
{
    /**
     * description
     *
     * @var ServiceReferentiel
     */
    private $serviceServiceReferentiel;

    /**
     *
     * @param ServiceReferentiel $serviceServiceReferentiel
     * @return self
     */
    public function setServiceServiceReferentiel( ServiceReferentiel $serviceServiceReferentiel )
    {
        $this->serviceServiceReferentiel = $serviceServiceReferentiel;
        return $this;
    }

    /**
     *
     * @return ServiceReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceServiceReferentiel()
    {
        if (empty($this->serviceServiceReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationServiceReferentiel');
        }else{
            return $this->serviceServiceReferentiel;
        }
    }

}