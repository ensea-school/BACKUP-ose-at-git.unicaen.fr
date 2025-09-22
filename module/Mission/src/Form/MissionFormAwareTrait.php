<?php

namespace Mission\Form;


/**
 * Description of MissionFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionFormAwareTrait
{
    protected ?MissionForm $formMission = null;



    /**
     * @param MissionForm $formMission
     *
     * @return self
     */
    public function setFormMission(?MissionForm $formMission)
    {
        $this->formMission = $formMission;

        return $this;
    }



    public function getFormMission(): ?MissionForm
    {
        if (!empty($this->formMission)) {
            return $this->formMission;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MissionForm::class);
    }
}