<?php

namespace Application\Form\VolumeHoraire\Traits;

use Application\Form\VolumeHoraire\SaisieCalendaire;

/**
 * Description of SaisieCalendaireAwareTrait
 *
 * @author UnicaenCode
 */
trait SaisieCalendaireAwareTrait
{
    protected ?SaisieCalendaire $formVolumeHoraireSaisieCalendaire = null;



    /**
     * @param SaisieCalendaire $formVolumeHoraireSaisieCalendaire
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisieCalendaire(?SaisieCalendaire $formVolumeHoraireSaisieCalendaire)
    {
        $this->formVolumeHoraireSaisieCalendaire = $formVolumeHoraireSaisieCalendaire;

        return $this;
    }



    public function getFormVolumeHoraireSaisieCalendaire(): ?SaisieCalendaire
    {
        if (!empty($this->formVolumeHoraireSaisieCalendaire)) {
            return $this->formVolumeHoraireSaisieCalendaire;
        }

        return \Application::$container->get('FormElementManager')->get(SaisieCalendaire::class);
    }
}