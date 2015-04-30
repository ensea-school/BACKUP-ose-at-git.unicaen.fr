<?php

namespace Application\Service\Traits;

use Application\Service\FormuleResultatVolumeHoraireReferentiel;
use Common\Exception\RuntimeException;

trait FormuleResultatVolumeHoraireReferentielAwareTrait
{
    /**
     * description
     *
     * @var FormuleResultatVolumeHoraireReferentiel
     */
    private $serviceFormuleResultatVolumeHoraireReferentiel;

    /**
     *
     * @param FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel( FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel )
    {
        $this->serviceFormuleResultatVolumeHoraireReferentiel = $serviceFormuleResultatVolumeHoraireReferentiel;
        return $this;
    }

    /**
     *
     * @return FormuleResultatVolumeHoraireReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceFormuleResultatVolumeHoraireReferentiel()
    {
        if (empty($this->serviceFormuleResultatVolumeHoraireReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationFormuleResultatVolumeHoraireReferentiel');
        }else{
            return $this->serviceFormuleResultatVolumeHoraireReferentiel;
        }
    }

}