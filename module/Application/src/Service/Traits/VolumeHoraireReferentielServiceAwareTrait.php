<?php

namespace Application\Service\Traits;

use Application\Service\VolumeHoraireReferentielService;

/**
 * Description of VolumeHoraireReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireReferentielServiceAwareTrait
{
    protected ?VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel;



    /**
     * @param VolumeHoraireReferentielService|null $serviceVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel( ?VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel )
    {
        $this->serviceVolumeHoraireReferentiel = $serviceVolumeHoraireReferentiel;

        return $this;
    }



    public function getServiceVolumeHoraireReferentiel(): ?VolumeHoraireReferentielService
    {
        if (!$this->serviceVolumeHoraireReferentiel){
            $this->serviceVolumeHoraireReferentiel = \Application::$container->get(VolumeHoraireReferentielService::class);
        }

        return $this->serviceVolumeHoraireReferentiel;
    }
}