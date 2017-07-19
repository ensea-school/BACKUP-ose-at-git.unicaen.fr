<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleVolumeHoraireReferentiel;
use RuntimeException;

/**
 * Description of FormuleVolumeHoraireReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleVolumeHoraireReferentielAwareInterface
{
    /**
     * @param FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceFormuleVolumeHoraireReferentiel( FormuleVolumeHoraireReferentiel $serviceFormuleVolumeHoraireReferentiel );



    /**
     * @return FormuleVolumeHoraireReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleVolumeHoraireReferentiel();
}