<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleVolumeHoraireReferentiel;

/**
 * Description of FormuleVolumeHoraireReferentielAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleVolumeHoraireReferentielAwareInterface
{
    /**
     * @param FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel
     * @return self
     */
    public function setFormuleVolumeHoraireReferentiel( FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel = null );



    /**
     * @return FormuleVolumeHoraireReferentiel
     */
    public function getFormuleVolumeHoraireReferentiel();
}