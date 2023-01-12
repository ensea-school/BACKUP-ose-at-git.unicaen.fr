<?php

namespace Mission\Form;


/**
 * Description of MissionTauxFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionTauxFormAwareTrait
{
    protected ?MissionTauxForm $formMissionTaux = null;



    /**
     * @param MissionTauxForm $formMissionTaux
     *
     * @return self
     */
    public function setFormMissionTaux(?MissionTauxForm $formMissionTaux)
    {
        $this->formMissionTaux = $formMissionTaux;

        return $this;
    }



    public function getFormMissionTaux(): ?MissionTauxForm
    {
        if (!empty($this->formMissionTaux)) {
            return $this->formMissionTaux;
        }

        return \Application::$container->get('FormElementManager')->get(MissionTauxForm::class);
    }
}