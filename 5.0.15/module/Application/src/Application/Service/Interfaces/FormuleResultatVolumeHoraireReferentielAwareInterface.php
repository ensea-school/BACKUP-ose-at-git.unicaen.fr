<?php

namespace Application\Service\Interfaces;

use Application\Service\FormuleResultatVolumeHoraireReferentiel;
use RuntimeException;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatVolumeHoraireReferentielAwareInterface
{
    /**
     * @param FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel
     * @return self
     */
    public function setServiceFormuleResultatVolumeHoraireReferentiel( FormuleResultatVolumeHoraireReferentiel $serviceFormuleResultatVolumeHoraireReferentiel );



    /**
     * @return FormuleResultatVolumeHoraireReferentielAwareInterface
     * @throws RuntimeException
     */
    public function getServiceFormuleResultatVolumeHoraireReferentiel();
}