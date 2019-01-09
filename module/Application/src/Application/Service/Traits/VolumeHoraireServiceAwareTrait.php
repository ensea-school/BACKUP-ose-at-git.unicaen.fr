<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireService;

/**
 * Description of VolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireServiceAwareTrait
{
    /**
     * @var VolumeHoraireService
     */
    private $serviceVolumeHoraire;



    /**
     * @param VolumeHoraireService $serviceVolumeHoraire
     *
     * @return self
     */
    public function setServiceVolumeHoraire(VolumeHoraireService $serviceVolumeHoraire)
    {
        $this->serviceVolumeHoraire = $serviceVolumeHoraire;

        return $this;
    }



    /**
     * @return VolumeHoraireService
     */
    public function getServiceVolumeHoraire()
    {
        if (empty($this->serviceVolumeHoraire)) {
            $this->serviceVolumeHoraire = \Application::$container->get(VolumeHoraireService::class);
        }

        return $this->serviceVolumeHoraire;
    }
}