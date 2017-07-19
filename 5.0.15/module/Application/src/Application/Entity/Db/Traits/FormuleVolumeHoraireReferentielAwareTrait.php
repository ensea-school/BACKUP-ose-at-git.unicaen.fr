<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleVolumeHoraireReferentiel;

/**
 * Description of FormuleVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireReferentielAwareTrait
{
    /**
     * @var FormuleVolumeHoraireReferentiel
     */
    private $formuleVolumeHoraireReferentiel;





    /**
     * @param FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel
     * @return self
     */
    public function setFormuleVolumeHoraireReferentiel( FormuleVolumeHoraireReferentiel $formuleVolumeHoraireReferentiel = null )
    {
        $this->formuleVolumeHoraireReferentiel = $formuleVolumeHoraireReferentiel;
        return $this;
    }



    /**
     * @return FormuleVolumeHoraireReferentiel
     */
    public function getFormuleVolumeHoraireReferentiel()
    {
        return $this->formuleVolumeHoraireReferentiel;
    }
}