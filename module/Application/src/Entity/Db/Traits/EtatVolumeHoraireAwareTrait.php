<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\EtatVolumeHoraire;

/**
 * Description of EtatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait EtatVolumeHoraireAwareTrait
{
    protected ?EtatVolumeHoraire $etatVolumeHoraire;



    /**
     * @param EtatVolumeHoraire|null $etatVolumeHoraire
     *
     * @return self
     */
    public function setEtatVolumeHoraire( ?EtatVolumeHoraire $etatVolumeHoraire )
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }



    public function getEtatVolumeHoraire(): ?EtatVolumeHoraire
    {
        return $this->etatVolumeHoraire;
    }
}