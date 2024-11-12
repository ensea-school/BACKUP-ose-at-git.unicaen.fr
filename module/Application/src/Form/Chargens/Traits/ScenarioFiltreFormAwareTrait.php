<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\ScenarioFiltreForm;

/**
 * Description of ScenarioFiltreFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioFiltreFormAwareTrait
{
    protected ?ScenarioFiltreForm $formChargensScenarioFiltre = null;



    /**
     * @param ScenarioFiltreForm $formChargensScenarioFiltre
     *
     * @return self
     */
    public function setFormChargensScenarioFiltre(?ScenarioFiltreForm $formChargensScenarioFiltre)
    {
        $this->formChargensScenarioFiltre = $formChargensScenarioFiltre;

        return $this;
    }



    public function getFormChargensScenarioFiltre(): ?ScenarioFiltreForm
    {
        if (!empty($this->formChargensScenarioFiltre)) {
            return $this->formChargensScenarioFiltre;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(ScenarioFiltreForm::class);
    }
}