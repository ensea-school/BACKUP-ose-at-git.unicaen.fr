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
    protected ?SaisieMultipleFieldset $formVolumeHoraireSaisieMultipleFieldset;



    /**
     * @param SaisieMultipleFieldset|null $formVolumeHoraireSaisieMultipleFieldset
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisieMultipleFieldset( ?SaisieMultipleFieldset $formVolumeHoraireSaisieMultipleFieldset )
    {
        $this->formVolumeHoraireSaisieMultipleFieldset = $formVolumeHoraireSaisieMultipleFieldset;

        return $this;
    }



    public function getFormVolumeHoraireSaisieMultipleFieldset(): ?SaisieMultipleFieldset
    {
        if (!$this->formVolumeHoraireSaisieMultipleFieldset){
            $this->formVolumeHoraireSaisieMultipleFieldset = \Application::$container->get('FormElementManager')->get(SaisieMultipleFieldset::class);
        }

        return $this->formVolumeHoraireSaisieMultipleFieldset;
    }
}