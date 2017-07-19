<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\FormuleResultatVolumeHoraire;

/**
 * Description of FormuleResultatVolumeHoraireAwareInterface
 *
 * @author UnicaenCode
 */
interface FormuleResultatVolumeHoraireAwareInterface
{
    /**
     * @param FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire
     * @return self
     */
    public function setFormuleResultatVolumeHoraire( FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire = null );



    /**
     * @return FormuleResultatVolumeHoraire
     */
    public function getFormuleResultatVolumeHoraire();
}