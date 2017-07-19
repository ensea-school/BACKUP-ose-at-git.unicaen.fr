<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\ScenarioForm;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setFormChargensScenario( ScenarioForm $formChargensScenario )
    {
        $this->formChargensScenario = $formChargensScenario;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ScenarioForm
     * @throws RuntimeException
     */
    public function getFormChargensScenario()
    {
        if (!empty($this->formChargensScenario)){
            return $this->formChargensScenario;
        }

        $serviceLocator = Module::$serviceLocator;
        if (! $serviceLocator) {
            if (!method_exists($this, 'getServiceLocator')) {
                throw new RuntimeException('La classe ' . get_class($this) . ' n\'a pas accès au ServiceLocator.');
            }

            $serviceLocator = $this->getServiceLocator();
            if (method_exists($serviceLocator, 'getServiceLocator')) {
                $serviceLocator = $serviceLocator->getServiceLocator();
            }
        }
        return $serviceLocator->get('FormElementManager')->get('ChargensScenario');
    }
}