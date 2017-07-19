<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleVolumeHoraire;

/**
 * Description of FormuleVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleVolumeHoraireAwareInterface
{
    /**
     * @param FormuleVolumeHoraire $formuleVolumeHoraire
     * @return self
     */
    public function setFormuleVolumeHoraire( FormuleVolumeHoraire $formuleVolumeHoraire = null );



    /**
     * @return FormuleVolumeHoraire
     */
    public function getFormuleVolumeHoraire();
}