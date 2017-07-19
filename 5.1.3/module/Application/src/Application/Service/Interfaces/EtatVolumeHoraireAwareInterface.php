<?php

namespace Application\Service\Interfaces;

use Application\Service\EtatVolumeHoraire;
use RuntimeException;

/**
 * Description of EtatVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface EtatVolumeHoraireAwareInterface
{
    /**
     * @param EtatVolumeHoraire $serviceEtatVolumeHoraire
     * @return self
     */
    public function setServiceEtatVolumeHoraire( EtatVolumeHoraire $serviceEtatVolumeHoraire );



    /**
     * @return EtatVolumeHoraireAwareInterface
     * @throws RuntimeException
     */
    public function getServiceEtatVolumeHoraire();
}