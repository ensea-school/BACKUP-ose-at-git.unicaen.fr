<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\EtatVolumeHoraire;

/**
 * Description of EtatVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface EtatVolumeHoraireAwareInterface
{
    /**
     * @param EtatVolumeHoraire $etatVolumeHoraire
     * @return self
     */
    public function setEtatVolumeHoraire( EtatVolumeHoraire $etatVolumeHoraire = null );



    /**
     * @return EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire();
}