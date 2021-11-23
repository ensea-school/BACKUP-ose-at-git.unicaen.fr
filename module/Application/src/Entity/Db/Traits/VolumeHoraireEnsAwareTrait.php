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
    /**
     * @var VolumeHoraireEns
     */
    private $volumeHoraireEns;





    /**
     * @param VolumeHoraireEns $volumeHoraireEns
     * @return self
     */
    public function setVolumeHoraireEns( VolumeHoraireEns $volumeHoraireEns = null )
    {
        $this->volumeHoraireEns = $volumeHoraireEns;
        return $this;
    }



    /**
     * @return VolumeHoraireEns
     */
    public function getVolumeHoraireEns()
    {
        return $this->volumeHoraireEns;
    }
}