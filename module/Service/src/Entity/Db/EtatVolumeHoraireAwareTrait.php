<?php

namespace Service\Entity\Db;

/**
 * Description of EtatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireAwareTrait
{
    protected ?EtatVolumeHoraire $etatVolumeHoraire = null;



    /**
     * @param EtatVolumeHoraire $etatVolumeHoraire
     *
     * @return self
     */
    public function setEtatVolumeHoraire(?EtatVolumeHoraire $etatVolumeHoraire)
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }



    public function getEtatVolumeHoraire(): ?EtatVolumeHoraire
    {
        return $this->etatVolumeHoraire;
    }
}