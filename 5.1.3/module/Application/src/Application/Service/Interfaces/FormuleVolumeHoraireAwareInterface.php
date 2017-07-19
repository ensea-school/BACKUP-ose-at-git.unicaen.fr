<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleVolumeHoraire;
use RuntimeException;

/**
 * Description of FormuleVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleVolumeHoraireAwareInterface
{
    /**
     * @param FormuleVolumeHoraire $serviceFormuleVolumeHoraire
     * @return self
     */
    public function setServiceFormuleVolumeHoraire( FormuleVolumeHoraire $serviceFormuleVolumeHoraire );



    /**
     * @return FormuleVolumeHoraireAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleVolumeHoraire();
}