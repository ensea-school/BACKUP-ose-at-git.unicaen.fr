<?php

namespace Application\Form\VolumeHoraire\Interfaces;

use Application\Form\VolumeHoraire\SaisieMultipleFieldset;

/**
 * Description of SaisieMultipleFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieMultipleFieldsetAwareInterface
{
    /**
     * @param SaisieMultipleFieldset|null $formVolumeHoraireSaisieMultipleFieldset
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisieMultipleFieldset( ?SaisieMultipleFieldset $formVolumeHoraireSaisieMultipleFieldset );



    public function getFormVolumeHoraireSaisieMultipleFieldset(): ?SaisieMultipleFieldset;
}