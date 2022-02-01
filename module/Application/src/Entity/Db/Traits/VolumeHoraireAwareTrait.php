<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VolumeHoraire;

/**
 * Description of VolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireAwareTrait
{
    protected ?VolumeHoraire $volumeHoraire;



    /**
     * @param VolumeHoraire|null $volumeHoraire
     *
     * @return self
     */
    public function setVolumeHoraire( ?VolumeHoraire $volumeHoraire )
    {
        $this->volumeHoraire = $volumeHoraire;

        return $this;
    }



    public function getVolumeHoraire(): ?VolumeHoraire
    {
        return $this->volumeHoraire;
    }
}