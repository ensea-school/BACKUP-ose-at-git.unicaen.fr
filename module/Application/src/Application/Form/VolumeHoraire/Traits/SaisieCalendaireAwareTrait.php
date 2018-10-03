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
    /**
     * @var Saisie
     */
    private $formVolumeHoraireSaisieCalendaire;



    /**
     * @param Saisie $formVolumeHoraireSaisieCalendaire
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisieCalendaire(Saisie $formVolumeHoraireSaisieCalendaire)
    {
        $this->formVolumeHoraireSaisieCalendaire = $formVolumeHoraireSaisieCalendaire;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return SaisieCalendaire
     */
    public function getFormVolumeHoraireSaisieCalendaire()
    {
        if (!empty($this->formVolumeHoraireSaisieCalendaire)) {
            return $this->formVolumeHoraireSaisieCalendaire;
        }

        return \Application::$container->get('FormElementManager')->get(SaisieCalendaire::class);
    }
}