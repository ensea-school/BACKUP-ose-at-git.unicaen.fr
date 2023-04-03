<?php

namespace OffreFormation\Entity\Db\Traits;

use OffreFormation\Entity\Db\VolumeHoraireEns;

/**
 * Description of VolumeHoraireEnsAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireEnsAwareTrait
{
    protected ?VolumeHoraireEns $volumeHoraireEns = null;



    /**
     * @param VolumeHoraireEns $volumeHoraireEns
     *
     * @return self
     */
    public function setVolumeHoraireEns( ?VolumeHoraireEns $volumeHoraireEns )
    {
        $this->volumeHoraireEns = $volumeHoraireEns;

        return $this;
    }



    public function getVolumeHoraireEns(): ?VolumeHoraireEns
    {
        return $this->volumeHoraireEns;
    }
}