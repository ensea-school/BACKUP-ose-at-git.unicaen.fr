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
    protected ?SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple = null;



    /**
     * @param SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple
     *
     * @return self
     */
    public function setFieldsetVolumeHoraireSaisieMultiple(?SaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple)
    {
        $this->fieldsetVolumeHoraireSaisieMultiple = $fieldsetVolumeHoraireSaisieMultiple;

        return $this;
    }



    public function getFieldsetVolumeHoraireSaisieMultiple(): ?SaisieMultipleFieldset
    {
        if (!empty($this->fieldsetVolumeHoraireSaisieMultiple)) {
            return $this->fieldsetVolumeHoraireSaisieMultiple;
        }

        return \Application::$container->get('FormElementManager')->get(SaisieMultipleFieldset::class);
    }
}