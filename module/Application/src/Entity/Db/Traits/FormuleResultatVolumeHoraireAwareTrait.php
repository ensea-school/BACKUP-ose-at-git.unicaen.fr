<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleResultatVolumeHoraire;

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