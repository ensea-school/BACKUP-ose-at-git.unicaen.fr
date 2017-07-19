<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatVolumeHoraireReferentielAwareInterface
{
    /**
     * @param FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel
     * @return self
     */
    public function setFormuleResultatVolumeHoraireReferentiel( FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel = null );



    /**
     * @return FormuleResultatVolumeHoraireReferentiel
     */
    public function getFormuleResultatVolumeHoraireReferentiel();
}