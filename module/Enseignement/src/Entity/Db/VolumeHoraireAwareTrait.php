<?php

namespace Enseignement\Entity\Db;

/**
 * Description of VolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireAwareTrait
{
    protected ?VolumeHoraire $volumeHoraire = null;



    /**
     * @param VolumeHoraire $volumeHoraire
     *
     * @return self
     */
    public function setVolumeHoraire(?VolumeHoraire $volumeHoraire)
    {
        $this->volumeHoraire = $volumeHoraire;

        return $this;
    }



    public function getVolumeHoraire(): ?VolumeHoraire
    {
        return $this->volumeHoraire;
    }
}