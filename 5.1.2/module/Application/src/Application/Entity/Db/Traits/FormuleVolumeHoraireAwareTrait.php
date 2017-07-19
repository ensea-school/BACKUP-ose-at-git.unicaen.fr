<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\FormuleVolumeHoraire;

/**
 * Description of FormuleVolumeHoraireAwareTrait
 *
 * @author UnicaenCode
 */
trait FormuleVolumeHoraireAwareTrait
{
    /**
     * @var FormuleVolumeHoraire
     */
    private $formuleVolumeHoraire;





    /**
     * @param FormuleVolumeHoraire $formuleVolumeHoraire
     * @return self
     */
    public function setFormuleVolumeHoraire( FormuleVolumeHoraire $formuleVolumeHoraire = null )
    {
        $this->formuleVolumeHoraire = $formuleVolumeHoraire;
        return $this;
    }



    /**
     * @return FormuleVolumeHoraire
     */
    public function getFormuleVolumeHoraire()
    {
        return $this->formuleVolumeHoraire;
    }
}