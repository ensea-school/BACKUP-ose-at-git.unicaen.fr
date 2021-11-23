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
    /**
     * @var FormuleResultatVolumeHoraire
     */
    private $formuleResultatVolumeHoraire;





    /**
     * @param FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire
     * @return self
     */
    public function setFormuleResultatVolumeHoraire( FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire = null )
    {
        $this->formuleResultatVolumeHoraire = $formuleResultatVolumeHoraire;
        return $this;
    }



    /**
     * @return FormuleResultatVolumeHoraire
     */
    public function getFormuleResultatVolumeHoraire()
    {
        return $this->formuleResultatVolumeHoraire;
    }
}