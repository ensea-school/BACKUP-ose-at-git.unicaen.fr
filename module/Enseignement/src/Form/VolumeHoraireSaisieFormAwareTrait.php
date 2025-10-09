<?php

namespace Enseignement\Form;

/**
 * Description of VolumeHoraireSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireSaisieFormAwareTrait
{
    protected ?VolumeHoraireSaisieForm $formVolumeHoraireSaisie = null;



    /**
     * @param VolumeHoraireSaisieForm $formVolumeHoraireSaisie
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisie(?VolumeHoraireSaisieForm $formVolumeHoraireSaisie)
    {
        $this->formVolumeHoraireSaisie = $formVolumeHoraireSaisie;

        return $this;
    }



    public function getFormVolumeHoraireSaisie(): ?VolumeHoraireSaisieForm
    {
        if (!empty($this->formVolumeHoraireSaisie)) {
            return $this->formVolumeHoraireSaisie;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(VolumeHoraireSaisieForm::class);
    }
}