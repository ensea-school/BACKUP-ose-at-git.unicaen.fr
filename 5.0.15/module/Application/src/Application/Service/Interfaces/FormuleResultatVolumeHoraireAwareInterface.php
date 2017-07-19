<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleResultatVolumeHoraire;
use RuntimeException;

/**
 * Description of FormuleResultatVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatVolumeHoraireAwareInterface
{
    /**
     * @param FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraire( FormuleResultatVolumeHoraire $serviceFormuleResultatVolumeHoraire );



    /**
     * @return FormuleResultatVolumeHoraireAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatVolumeHoraire();
}