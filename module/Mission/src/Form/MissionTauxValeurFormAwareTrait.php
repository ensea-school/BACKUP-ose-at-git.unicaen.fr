<?php

namespace Mission\Form;


/**
 * Description of MissionTauxFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionTauxValeurFormAwareTrait
{
    protected ?MissionTauxValeurForm $formMissionTauxValeur = null;



    /**
     * @param MissionTauxValeurForm $formMissionTauxValeur
     *
     * @return self
     */
    public function setFormMissionTauxValeur(?MissionTauxForm $formMissionTauxValeur)
    {
        $this->formMissionTauxValeur = $formMissionTauxValeur;

        return $this;
    }



    public function getFormMissionTauxValeur(): ?MissionTauxValeurForm
    {
        if (!empty($this->formMissionTauxValeur)) {
            return $this->formMissionTauxValeur;
        }

        return \Application::$container->get('FormElementManager')->get(MissionTauxValeurForm::class);
    }
}