<?php

namespace Application\Service\Interfaces;

use Application\Service\VolumeHoraireReferentiel;
use RuntimeException;

/**
 * Description of VolumeHoraireReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface VolumeHoraireReferentielAwareInterface
{
    /**
     * @param VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceVolumeHoraireReferentiel( VolumeHoraireReferentiel $serviceVolumeHoraireReferentiel );



    /**
     * @return VolumeHoraireReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceVolumeHoraireReferentiel();
}