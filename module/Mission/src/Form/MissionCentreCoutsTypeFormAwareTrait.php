<?php

namespace Mission\Form;


/**
 * Description of MissionCentreCoutsTypeFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionCentreCoutsTypeFormAwareTrait
{
    protected ?MissionCentreCoutsTypeForm $formMissionCentreCoutsType = null;



    /**
     * @param MissionCentreCoutsTypeForm $formMissionCentreCoutsType
     *
     * @return self
     */
    public function setFormMissionCentreCoutsType(?MissionCentreCoutsTypeForm $formMissionCentreCoutsType)
    {
        $this->formMissionCentreCoutsType = $formMissionCentreCoutsType;

        return $this;
    }



    public function getFormMissionCentreCoutsType(): ?MissionCentreCoutsTypeForm
    {
        if (!empty($this->formMissionCentreCoutsType)) {
            return $this->formMissionCentreCoutsType;
        }

        return \Application::$container->get('FormElementManager')->get(MissionCentreCoutsTypeForm::class);
    }
}
