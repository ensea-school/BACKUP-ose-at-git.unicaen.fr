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
    /**
     * @var Saisie
     */
    private $formVolumeHoraireSaisie;



    /**
     * @param Saisie $formVolumeHoraireSaisie
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisie(Saisie $formVolumeHoraireSaisie)
    {
        $this->formVolumeHoraireSaisie = $formVolumeHoraireSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return Saisie
     */
    public function getFormVolumeHoraireSaisie()
    {
        if (!empty($this->formVolumeHoraireSaisie)) {
            return $this->formVolumeHoraireSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('VolumeHoraireSaisie');
    }
}