<?php

namespace Application\Form\VolumeHoraire\Traits;

use Application\Form\VolumeHoraire\Saisie;

/**
 * Description of SaisieAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieAwareTrait
{
    protected ?Saisie $formVolumeHoraireSaisie = null;



    /**
     * @param Saisie $formVolumeHoraireSaisie
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisie(?Saisie $formVolumeHoraireSaisie)
    {
        $this->formVolumeHoraireSaisie = $formVolumeHoraireSaisie;

        return $this;
    }



    public function getFormVolumeHoraireSaisie(): ?Saisie
    {
        if (!empty($this->formVolumeHoraireSaisie)) {
            return $this->formVolumeHoraireSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(Saisie::class);
    }
}