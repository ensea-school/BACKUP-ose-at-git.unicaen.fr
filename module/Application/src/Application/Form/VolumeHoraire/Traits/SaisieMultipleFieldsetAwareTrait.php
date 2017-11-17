<?php

namespace Application\Form\VolumeHoraire\Traits;

use Application\Form\VolumeHoraire\SaisieMultipleFieldset;

/**
 * Description of SaisieMultipleFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieMultipleFieldsetAwareTrait
{
    /**
     * @var SaisieMultipleFieldset
     */
    private $fieldsetVolumeHoraireSaisieMultiple;



    /**
     * @param SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple
     *
     * @return self
     */
    public function setFieldsetVolumeHoraireSaisieMultiple(SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple)
    {
        $this->fieldsetVolumeHoraireSaisieMultiple = $fieldsetVolumeHoraireSaisieMultiple;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return SaisieMultipleFieldset
     */
    public function getFieldsetVolumeHoraireSaisieMultiple()
    {
        if (!empty($this->fieldsetVolumeHoraireSaisieMultiple)) {
            return $this->fieldsetVolumeHoraireSaisieMultiple;
        }

        return \Application::$container->get('FormElementManager')->get('VolumeHoraireSaisieMultipleFieldset');
    }
}