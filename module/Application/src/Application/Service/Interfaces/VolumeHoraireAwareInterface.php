<?php

namespace Application\Service\Interfaces;

use Application\Service\VolumeHoraire;
use RuntimeException;

/**
 * Description of VolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface VolumeHoraireAwareInterface
{
    /**
     * @param VolumeHoraire $serviceVolumeHoraire
     * @return self
     */
    public function setServiceVolumeHoraire( VolumeHoraire $serviceVolumeHoraire );



    /**
     * @return VolumeHoraireAwareInterface
     * @throws RuntimeException
     */
    public function getServiceVolumeHoraire();
}