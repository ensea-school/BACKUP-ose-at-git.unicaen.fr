<?php

namespace Referentiel\Service;

/**
 * Description of VolumeHoraireReferentielServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireReferentielServiceAwareTrait
{
    protected ?VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel = null;



    /**
     * @param VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel(?VolumeHoraireReferentielService $serviceVolumeHoraireReferentiel)
    {
        $this->serviceVolumeHoraireReferentiel = $serviceVolumeHoraireReferentiel;

        return $this;
    }



    public function getServiceVolumeHoraireReferentiel(): ?VolumeHoraireReferentielService
    {
        if (empty($this->serviceVolumeHoraireReferentiel)) {
            $this->serviceVolumeHoraireReferentiel = \Unicaen\Framework\Application\Application::getInstance()->container()->get(VolumeHoraireReferentielService::class);
        }

        return $this->serviceVolumeHoraireReferentiel;
    }
}