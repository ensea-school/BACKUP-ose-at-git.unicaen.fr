<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VolumeHoraire;

/**
 * Description of VolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface VolumeHoraireAwareInterface
{
    /**
     * @param VolumeHoraire $volumeHoraire
     * @return self
     */
    public function setVolumeHoraire( VolumeHoraire $volumeHoraire = null );



    /**
     * @return VolumeHoraire
     */
    public function getVolumeHoraire();
}