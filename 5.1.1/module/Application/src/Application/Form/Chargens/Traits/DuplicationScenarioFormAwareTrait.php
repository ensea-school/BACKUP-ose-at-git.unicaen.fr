<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\DuplicationScenarioForm;
use Application\Module;
use RuntimeException;

/**
 * Description of DuplicationScenarioFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DuplicationScenarioFormAwareTrait
{
    /**
     * @var DuplicationScenarioForm
     */
    private $formChargensDuplicationScenario;





    /**
     * @param DuplicationScenarioForm $formChargensDuplicationScenario
     * @return self
     */
    public function setFormChargensDuplicationScenario( DuplicationScenarioForm $formChargensDuplicationScenario )
    {
        $this->formChargensDuplicationScenario = $formChargensDuplicationScenario;
        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DuplicationScenarioForm
     * @throws RuntimeException
     */
    public function getFormChargensDuplicationScenario()
    {
        if (!empty($this->formChargensDuplicationScenario)){
            return $this->formChargensDuplicationScenario;
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
        return $serviceLocator->get('FormElementManager')->get('ChargensDuplicationScenario');
    }
}