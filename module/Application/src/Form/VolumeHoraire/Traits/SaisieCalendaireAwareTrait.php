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
    protected ?SaisieCalendaire $formVolumeHoraireSaisieCalendaire;



    /**
     * @param SaisieCalendaire|null $formVolumeHoraireSaisieCalendaire
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisieCalendaire( ?SaisieCalendaire $formVolumeHoraireSaisieCalendaire )
    {
        $this->formVolumeHoraireSaisieCalendaire = $formVolumeHoraireSaisieCalendaire;

        return $this;
    }



    public function getFormVolumeHoraireSaisieCalendaire(): ?SaisieCalendaire
    {
        if (!$this->formVolumeHoraireSaisieCalendaire){
            $this->formVolumeHoraireSaisieCalendaire = \Application::$container->get('FormElementManager')->get(SaisieCalendaire::class);
        }

        return $this->formVolumeHoraireSaisieCalendaire;
    }
}