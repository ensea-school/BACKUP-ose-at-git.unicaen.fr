<?php

namespace OffreFormation\Service\Traits;

use OffreFormation\Service\VolumeHoraireEnsService;

/**
 * Description of VolumeHoraireEnsServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireEnsServiceAwareTrait
{
    protected ?VolumeHoraireEnsService $serviceVolumeHoraireEns = null;



    /**
     * @param VolumeHoraireEnsService $serviceVolumeHoraireEns
     *
     * @return self
     */
    public function setServiceVolumeHoraireEns(?VolumeHoraireEnsService $serviceVolumeHoraireEns)
    {
        $this->serviceVolumeHoraireEns = $serviceVolumeHoraireEns;

        return $this;
    }



    public function getServiceVolumeHoraireEns(): ?VolumeHoraireEnsService
    {
        if (empty($this->serviceVolumeHoraireEns)) {
            $this->serviceVolumeHoraireEns = \Framework\Application\Application::getInstance()->container()->get(VolumeHoraireEnsService::class);
        }

        return $this->serviceVolumeHoraireEns;
    }
}