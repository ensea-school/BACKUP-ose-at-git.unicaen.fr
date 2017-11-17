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
    /**
     * @var ScenarioFiltreForm
     */
    private $formChargensScenarioFiltre;



    /**
     * @param ScenarioFiltreForm $formChargensScenarioFiltre
     *
     * @return self
     */
    public function setFormChargensScenarioFiltre(ScenarioFiltreForm $formChargensScenarioFiltre)
    {
        $this->formChargensScenarioFiltre = $formChargensScenarioFiltre;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ScenarioFiltreForm
     */
    public function getFormChargensScenarioFiltre()
    {
        if (!empty($this->formChargensScenarioFiltre)) {
            return $this->formChargensScenarioFiltre;
        }

        $serviceLocator = \Application::$container;

        return \Application::$container->get('FormElementManager')->get('ChargensScenarioFiltre');
    }
}