<?php

namespace Mission\Form;



/**
 * Description of TauxFormAwareTrait
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
    public function setFormMissionType(?MissionTypeForm $formMissionType)
    {
        $this->formMissionType = $formMissionType;

        return $this;
    }



    public function getFormMissionType(): ?MissionTypeForm
    {
        if (!empty($this->formMissionType)) {
            return $this->formMissionType;
        }

         return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(MissionTypeForm::class);
    }
}