<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VolumeHoraireEns;

/**
 * Description of VolumeHoraireEnsAwareInterface
 *
 * @author UnicaenCode
 */
interface VolumeHoraireEnsAwareInterface
{
    /**
     * @param VolumeHoraireEns $volumeHoraireEns
     * @return self
     */
    public function setVolumeHoraireEns( VolumeHoraireEns $volumeHoraireEns = null );



    /**
     * @return VolumeHoraireEns
     */
    public function getVolumeHoraireEns();
}