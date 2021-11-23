<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\VolumeHoraireReferentiel;

/**
 * Description of VolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireReferentielAwareTrait
{
    /**
     * @var VolumeHoraireReferentiel
     */
    private $volumeHoraireReferentiel;





    /**
     * @param VolumeHoraireReferentiel $volumeHoraireReferentiel
     * @return self
     */
    public function setVolumeHoraireReferentiel( VolumeHoraireReferentiel $volumeHoraireReferentiel = null )
    {
        $this->volumeHoraireReferentiel = $volumeHoraireReferentiel;
        return $this;
    }



    /**
     * @return VolumeHoraireReferentiel
     */
    public function getVolumeHoraireReferentiel()
    {
        return $this->volumeHoraireReferentiel;
    }
}