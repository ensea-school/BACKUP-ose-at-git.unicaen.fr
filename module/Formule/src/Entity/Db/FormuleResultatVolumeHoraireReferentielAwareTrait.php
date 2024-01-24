<?php

namespace Formule\Entity\Db;

/**
 * Description of FormuleResultatVolumeHoraireReferentielAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireReferentielAwareTrait
{
    protected ?FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel = null;



    /**
     * @param FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel
     *
     * @return self
     */
    public function setFormuleResultatVolumeHoraireReferentiel( ?FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel )
    {
        $this->formuleResultatVolumeHoraireReferentiel = $formuleResultatVolumeHoraireReferentiel;

        return $this;
    }



    public function getFormuleResultatVolumeHoraireReferentiel(): ?FormuleResultatVolumeHoraireReferentiel
    {
        return $this->formuleResultatVolumeHoraireReferentiel;
    }
}