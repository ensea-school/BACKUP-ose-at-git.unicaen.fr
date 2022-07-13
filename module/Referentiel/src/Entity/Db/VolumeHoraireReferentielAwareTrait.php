<?php

namespace Referentiel\Entity\Db;

/**
 * Description of VolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireReferentielAwareTrait
{
    protected ?VolumeHoraireReferentiel $volumeHoraireReferentiel = null;



    /**
     * @param VolumeHoraireReferentiel $volumeHoraireReferentiel
     *
     * @return self
     */
    public function setVolumeHoraireReferentiel(?VolumeHoraireReferentiel $volumeHoraireReferentiel)
    {
        $this->volumeHoraireReferentiel = $volumeHoraireReferentiel;

        return $this;
    }



    public function getVolumeHoraireReferentiel(): ?VolumeHoraireReferentiel
    {
        return $this->volumeHoraireReferentiel;
    }
}