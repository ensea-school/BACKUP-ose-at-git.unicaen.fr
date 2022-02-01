<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VolumeHoraireEns;

/**
 * Description of VolumeHoraireEnsAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireEnsAwareTrait
{
    protected ?VolumeHoraireEns $volumeHoraireEns;



    /**
     * @param VolumeHoraireEns|null $volumeHoraireEns
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