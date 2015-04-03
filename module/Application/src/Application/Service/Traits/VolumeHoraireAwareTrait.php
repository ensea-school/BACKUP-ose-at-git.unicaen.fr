<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraire;
use Common\Exception\RuntimeException;

trait VolumeHoraireAwareTrait
{
    /**
     * description
     *
     * @var VolumeHoraire
     */
    private $serviceVolumeHoraire;

    /**
     *
     * @param VolumeHoraire $serviceVolumeHoraire
     * @return self
     */
    public function setServiceVolumeHoraire( VolumeHoraire $serviceVolumeHoraire )
    {
        $this->serviceVolumeHoraire = $serviceVolumeHoraire;
        return $this;
    }

    /**
     *
     * @return VolumeHoraire
     * @throws \Common\Exception\RuntimeException
     */
    public function getServiceVolumeHoraire()
    {
        if (empty($this->serviceVolumeHoraire)){
            if (! method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException( 'La classe '.get_class($this).' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }

            return $serviceLocator->get('applicationVolumeHoraire');
        }else{
            return $this->serviceVolumeHoraire;
        }
    }

}