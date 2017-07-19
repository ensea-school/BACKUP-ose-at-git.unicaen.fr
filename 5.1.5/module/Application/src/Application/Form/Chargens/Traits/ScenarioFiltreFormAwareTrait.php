<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\ScenarioFiltreForm;
use Application\Module;
use RuntimeException;

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
     * @return self
     */
    public function setFormChargensScenarioFiltre( ScenarioFiltreForm $formChargensScenarioFiltre )
    {
        $this->formChargensScenarioFiltre = $formChargensScenarioFiltre;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return ScenarioFiltreForm
     * @throws RuntimeException
     */
    public function getFormChargensScenarioFiltre()
    {
        if (!empty($this->formChargensScenarioFiltre)){
            return $this->formChargensScenarioFiltre;
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
        return $serviceLocator->get('FormElementManager')->get('ChargensScenarioFiltre');
    }
}