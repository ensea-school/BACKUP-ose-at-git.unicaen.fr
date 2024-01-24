<?php

namespace Formule\Entity\Db;

/**
 * Description of FormuleResultatVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleResultatVolumeHoraireAwareTrait
{
    protected ?FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire = null;



    /**
     * @param FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire
     *
     * @return self
     */
    public function setFormuleResultatVolumeHoraire( ?FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire )
    {
        $this->formuleResultatVolumeHoraire = $formuleResultatVolumeHoraire;

        return $this;
    }



    public function getFormuleResultatVolumeHoraire(): ?FormuleResultatVolumeHoraire
    {
        return $this->formuleResultatVolumeHoraire;
    }
}