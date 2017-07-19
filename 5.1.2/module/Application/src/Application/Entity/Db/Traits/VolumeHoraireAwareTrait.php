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
    /**
     * @var VolumeHoraire
     */
    private $volumeHoraire;





    /**
     * @param VolumeHoraire $volumeHoraire
     * @return self
     */
    public function setVolumeHoraire( VolumeHoraire $volumeHoraire = null )
    {
        $this->volumeHoraire = $volumeHoraire;
        return $this;
    }



    /**
     * @return VolumeHoraire
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }
}