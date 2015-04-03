<?php

namespace Application\Service\Traits;

use Application\Service\FormuleVolumeHoraireReferentiel;
use Common\Exception\RuntimeException;

trait FormuleVolumeHoraireReferentielAwareTrait
{
    /**
     * description
     *
     * @var FormuleVolumeHoraireReferentiel
     */
    private $serviceFormuleVolumeHoraireReferentiel;

    /**
     *
     * @param FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceFormuleVolumeHoraireReferentiel( FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel )
    {
        $this->serviceFormuleVolumeHoraireReferentiel = $serviceFormuleVolumeHoraireReferentiel;
        return $this;
    }

    /**
     *
     * @return FormuleVolumeHoraireReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleVolumeHoraireReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleVolumeHoraireReferentiel');
        }else{
            return $this->serviceFormuleVolumeHoraireReferentiel;
        }
    }

}