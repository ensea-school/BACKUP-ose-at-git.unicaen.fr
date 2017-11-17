<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraire;

/**
 * Description of VolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireAwareTrait
{
    /**
     * @var VolumeHoraire
     */
    private $serviceVolumeHoraire;



    /**
     * @param VolumeHoraire $serviceVolumeHoraire
     *
     * @return self
     */
    public function setServiceVolumeHoraire(VolumeHoraire $serviceVolumeHoraire)
    {
        $this->serviceVolumeHoraire = $serviceVolumeHoraire;

        return $this;
    }



    /**
     * @return VolumeHoraire
     */
    public function getServiceVolumeHoraire()
    {
        if (empty($this->serviceVolumeHoraire)) {
            $this->serviceVolumeHoraire = \Application::$container->get('ApplicationVolumeHoraire');
        }

        return $this->serviceVolumeHoraire;
    }
}