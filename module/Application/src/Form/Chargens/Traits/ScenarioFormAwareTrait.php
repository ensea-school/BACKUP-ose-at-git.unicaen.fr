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
    /**
     * @var ScenarioForm
     */
    private $formChargensScenario;



    /**
     * @param ScenarioForm $formChargensScenario
     *
     * @return self
     */
    public function setFormChargensScenario(ScenarioForm $formChargensScenario)
    {
        $this->formChargensScenario = $formChargensScenario;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ScenarioForm
     */
    public function getFormChargensScenario()
    {
        if (!empty($this->formChargensScenario)) {
            return $this->formChargensScenario;
        }

        return \Application::$container->get('FormElementManager')->get(ScenarioForm::class);
    }
}