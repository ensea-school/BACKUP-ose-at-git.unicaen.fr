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
    /**
     * @var EtatVolumeHoraire
     */
    private $etatVolumeHoraire;





    /**
     * @param EtatVolumeHoraire $etatVolumeHoraire
     * @return self
     */
    public function setEtatVolumeHoraire( EtatVolumeHoraire $etatVolumeHoraire = null )
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;
        return $this;
    }



    /**
     * @return EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }
}