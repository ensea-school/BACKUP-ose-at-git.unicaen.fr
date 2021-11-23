<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielAwareTrait
{
    /**
     * @var FormuleResultatVolumeHoraireReferentiel
     */
    private $formuleResultatVolumeHoraireReferentiel;





    /**
     * @param FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel
     * @return self
     */
    public function setFormuleResultatVolumeHoraireReferentiel( FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel = null )
    {
        $this->formuleResultatVolumeHoraireReferentiel = $formuleResultatVolumeHoraireReferentiel;
        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraireReferentiel
     */
    public function getFormuleResultatVolumeHoraireReferentiel()
    {
        return $this->formuleResultatVolumeHoraireReferentiel;
    }
}