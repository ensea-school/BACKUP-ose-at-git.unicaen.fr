<?php

namespace Application\Form\VolumeHoraire\Interfaces;

use Application\Form\VolumeHoraire\Saisie;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie|null $formVolumeHoraireSaisie
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisie( ?Saisie $formVolumeHoraireSaisie );



    public function getFormVolumeHoraireSaisie(): ?Saisie;
}