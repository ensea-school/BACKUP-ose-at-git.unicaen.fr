<?php

namespace Application\Form\VolumeHoraireReferentiel\Interfaces;

use Application\Form\VolumeHoraireReferentiel\SaisieMultipleFieldset;
use RuntimeException;

/**
 * Description of SaisieMultipleFieldsetAwareInterface
 *
 * @author UnicaenCode
 */
interface SaisieMultipleFieldsetAwareInterface
{
    /**
     * @param SaisieMultipleFieldset $fieldsetVolumeHoraireReferentielSaisieMultiple
     * @return self
     */
    public function setFieldsetVolumeHoraireReferentielSaisieMultiple( SaisieMultipleFieldset $fieldsetVolumeHoraireReferentielSaisieMultiple );



    /**
     * @return SaisieMultipleFieldsetAwareInterface
     * @throws RuntimeException
     */
    public function getFieldsetVolumeHoraireReferentielSaisieMultiple();
}