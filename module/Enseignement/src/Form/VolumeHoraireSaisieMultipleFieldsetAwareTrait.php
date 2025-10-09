<?php

namespace Enseignement\Form;

/**
 * Description of VolumeHoraireSaisieMultipleFieldsetAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireSaisieMultipleFieldsetAwareTrait
{
    protected ?VolumeHoraireSaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple = null;



    /**
     * @param VolumeHoraireSaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple
     *
     * @return self
     */
    public function setFieldsetVolumeHoraireSaisieMultiple(?VolumeHoraireSaisieMultipleFieldset $fieldsetVolumeHoraireSaisieMultiple)
    {
        $this->fieldsetVolumeHoraireSaisieMultiple = $fieldsetVolumeHoraireSaisieMultiple;

        return $this;
    }



    public function getFieldsetVolumeHoraireSaisieMultiple(): ?VolumeHoraireSaisieMultipleFieldset
    {
        if (!empty($this->fieldsetVolumeHoraireSaisieMultiple)) {
            return $this->fieldsetVolumeHoraireSaisieMultiple;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(VolumeHoraireSaisieMultipleFieldset::class);
    }
}