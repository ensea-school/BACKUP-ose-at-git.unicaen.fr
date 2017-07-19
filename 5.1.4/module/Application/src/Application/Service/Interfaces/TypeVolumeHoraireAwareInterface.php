<?php

namespace Application\Service\Interfaces;

use Application\Service\TypeVolumeHoraire;
use RuntimeException;

/**
 * Description of TypeVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface TypeVolumeHoraireAwareInterface
{
    /**
     * @param TypeVolumeHoraire $serviceTypeVolumeHoraire
     * @return self
     */
    public function setServiceTypeVolumeHoraire( TypeVolumeHoraire $serviceTypeVolumeHoraire );



    /**
     * @return TypeVolumeHoraireAwareInterface
     * @throws RuntimeException
     */
    public function getServiceTypeVolumeHoraire();
}