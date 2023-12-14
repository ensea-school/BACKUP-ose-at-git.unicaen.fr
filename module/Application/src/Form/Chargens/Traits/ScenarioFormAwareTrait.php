<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\ScenarioForm;

/**
 * Description of ScenarioFormAwareTrait
 *
 * @author UnicaenCode
 */
trait ScenarioFormAwareTrait
{
    protected ?ScenarioForm $formChargensScenario = null;



    /**
     * @param ScenarioForm $formChargensScenario
     *
     * @return self
     */
    public function setFormChargensScenario(?ScenarioForm $formChargensScenario)
    {
        $this->formChargensScenario = $formChargensScenario;

        return $this;
    }



    public function getFormChargensScenario(): ?ScenarioForm
    {
        if (!empty($this->formChargensScenario)) {
            return $this->formChargensScenario;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(ScenarioForm::class);
    }
}