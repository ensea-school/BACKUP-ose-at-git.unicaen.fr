<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\VolumeHoraireReferentiel;

/**
 * Description of VolumeHoraireReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface VolumeHoraireReferentielAwareInterface
{
    /**
     * @param VolumeHoraireReferentiel $volumeHoraireReferentiel
     * @return self
     */
    public function setVolumeHoraireReferentiel( VolumeHoraireReferentiel $volumeHoraireReferentiel = null );



    /**
     * @return VolumeHoraireReferentiel
     */
    public function getVolumeHoraireReferentiel();
}