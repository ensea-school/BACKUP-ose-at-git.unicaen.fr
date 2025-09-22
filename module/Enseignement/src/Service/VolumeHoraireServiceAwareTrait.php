<?php

namespace Enseignement\Service;

/**
 * Description of VolumeHoraireServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireServiceAwareTrait
{
    protected ?VolumeHoraireService $serviceVolumeHoraire = null;



    /**
     * @param VolumeHoraireService $serviceVolumeHoraire
     *
     * @return self
     */
    public function setServiceVolumeHoraire(?VolumeHoraireService $serviceVolumeHoraire)
    {
        $this->serviceVolumeHoraire = $serviceVolumeHoraire;

        return $this;
    }



    public function getServiceVolumeHoraire(): ?VolumeHoraireService
    {
        if (empty($this->serviceVolumeHoraire)) {
            $this->serviceVolumeHoraire = \Framework\Application\Application::getInstance()->container()->get(VolumeHoraireService::class);
        }

        return $this->serviceVolumeHoraire;
    }
}