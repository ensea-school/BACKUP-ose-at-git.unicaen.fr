<?php

namespace Application\Form\VolumeHoraire\Interfaces;

use Application\Form\VolumeHoraire\SaisieMultipleFieldset;
use RuntimeException;

/**
 * Description of SaisieMultipleFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieMultipleFieldsetAwareInterface
{
    /**
     * @param SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple
     * @return self
     */
    public function setFieldsetVolumeHoraireSaisieMultiple( SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple );



    /**
     * @return SaisieMultipleFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetVolumeHoraireSaisieMultiple();
}