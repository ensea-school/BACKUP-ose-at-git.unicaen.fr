<?php

namespace Mission\Form;


/**
 * Description of MissionTauxFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionTypeFormAwareTrait
{
    protected ?MissionTypeForm $formMissionType = null;



    /**
     * @param MissionTypeForm $formMissionType
     *
     * @return self
     */
    public function setFormMissionType(?MissionTauxForm $formMissionType)
    {
        $this->formMissionType = $formMissionType;

        return $this;
    }



    public function getFormMissionType(): ?MissionTypeForm
    {
        if (!empty($this->formMissionType)) {
            return $this->formMissionType;
        }

        return \Application::$container->get('FormElementManager')->get(MissionTypeForm::class);
    }
}