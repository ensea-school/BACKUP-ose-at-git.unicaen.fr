<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireReferentiel;
use Common\Exception\RuntimeException;

trait VolumeHoraireReferentielAwareTrait
{
    /**
     * description
     *
     * @var VolumeHoraireReferentiel
     */
    private $serviceVolumeHoraireReferentiel;

    /**
     *
     * @param VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel( VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel )
    {
        $this->serviceVolumeHoraireReferentiel = $serviceVolumeHoraireReferentiel;
        return $this;
    }

    /**
     *
     * @return VolumeHoraireReferentiel
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceVolumeHoraireReferentiel()
    {
        if (empty($this->serviceVolumeHoraireReferentiel)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationVolumeHoraireReferentiel');
        }else{
            return $this->serviceVolumeHoraireReferentiel;
        }
    }

}