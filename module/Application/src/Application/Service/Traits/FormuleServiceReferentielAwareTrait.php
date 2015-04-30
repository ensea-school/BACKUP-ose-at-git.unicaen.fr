<?php

namespace Application\Service\Traits;

use Application\Service\FormuleServiceReferentiel;
use Common\Exception\RuntimeException;

trait FormuleServiceReferentielAwareTrait
{
    /**
     * description
     *
     * @var FormuleServiceReferentiel
     */
    private $serviceFormuleServiceReferentiel;

    /**
     *
     * @param FormuleServiceReferentiel $serviceFormuleServiceReferentiel
     * @return self
     */
    public function setServiceFormuleServiceReferentiel( FormuleServiceReferentiel $serviceFormuleServiceReferentiel )
    {
        $this->serviceFormuleServiceReferentiel = $serviceFormuleServiceReferentiel;
        return $this;
    }

    /**
     *
     * @return FormuleServiceReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleServiceReferentiel()
    {
        if (empty($this->serviceFormuleServiceReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleServiceReferentiel');
        }else{
            return $this->serviceFormuleServiceReferentiel;
        }
    }

}