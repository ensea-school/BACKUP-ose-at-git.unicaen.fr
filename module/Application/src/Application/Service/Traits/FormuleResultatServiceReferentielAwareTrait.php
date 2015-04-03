<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatServiceReferentiel;
use Common\Exception\RuntimeException;

trait FormuleResultatServiceReferentielAwareTrait
{
    /**
     * description
     *
     * @var FormuleResultatServiceReferentiel
     */
    private $serviceFormuleResultatServiceReferentiel;

    /**
     *
     * @param FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel
     * @return self
     */
    public function setServiceFormuleResultatServiceReferentiel( FormuleResultatServiceReferentiel $serviceFormuleResultatServiceReferentiel )
    {
        $this->serviceFormuleResultatServiceReferentiel = $serviceFormuleResultatServiceReferentiel;
        return $this;
    }

    /**
     *
     * @return FormuleResultatServiceReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleResultatServiceReferentiel()
    {
        if (empty($this->serviceFormuleResultatServiceReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleResultatServiceReferentiel');
        }else{
            return $this->serviceFormuleResultatServiceReferentiel;
        }
    }

}