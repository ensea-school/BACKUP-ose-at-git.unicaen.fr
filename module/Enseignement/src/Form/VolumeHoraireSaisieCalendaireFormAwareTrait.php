<?php

namespace Enseignement\Form;

/**
 * Description of VolumeHoraireSaisieCalendaireFormAwareTrait
 *
 * @author UnicaenCode
 */
trait VolumeHoraireSaisieCalendaireFormAwareTrait
{
    protected ?VolumeHoraireSaisieCalendaireForm $formVolumeHoraireSaisieCalendaire = null;



    /**
     * @param VolumeHoraireSaisieCalendaireForm $formVolumeHoraireSaisieCalendaire
     *
     * @return self
     */
    public function setFormVolumeHoraireSaisieCalendaire(?VolumeHoraireSaisieCalendaireForm $formVolumeHoraireSaisieCalendaire)
    {
        $this->formVolumeHoraireSaisieCalendaire = $formVolumeHoraireSaisieCalendaire;

        return $this;
    }



    public function getFormVolumeHoraireSaisieCalendaire(): ?VolumeHoraireSaisieCalendaireForm
    {
        if (!empty($this->formVolumeHoraireSaisieCalendaire)) {
            return $this->formVolumeHoraireSaisieCalendaire;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(VolumeHoraireSaisieCalendaireForm::class);
    }
}