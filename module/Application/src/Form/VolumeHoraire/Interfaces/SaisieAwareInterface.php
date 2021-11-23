<?php

namespace Application\Form\VolumeHoraire\Interfaces;

use Application\Form\VolumeHoraire\Saisie;
use RuntimeException;

/**
 * Description of SaisieAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieAwareInterface
{
    /**
     * @param Saisie $formVolumeHoraireSaisie
     * @return self
     */
    public function setFormVolumeHoraireSaisie( Saisie $formVolumeHoraireSaisie );



    /**
     * @return SaisieAwareInterface
     * @throws RuntimeException
     */
    public function getFormVolumeHoraireSaisie();
}