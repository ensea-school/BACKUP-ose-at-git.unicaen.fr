<?php

namespace Mission\Form;


/**
 * Description of MissionSuiviFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionSuiviFormAwareTrait
{
    protected ?MissionSuiviForm $formMissionSuivi = null;



    /**
     * @param MissionSuiviForm $formMissionSuivi
     *
     * @return self
     */
    public function setFormMissionSuivi(?MissionSuiviForm $formMissionSuivi)
    {
        $this->formMissionSuivi = $formMissionSuivi;

        return $this;
    }



    public function getFormMissionSuivi(): ?MissionSuiviForm
    {
        if (!empty($this->formMissionSuivi)) {
            return $this->formMissionSuivi;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MissionSuiviForm::class);
    }
}