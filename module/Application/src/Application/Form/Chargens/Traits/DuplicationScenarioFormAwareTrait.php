<?php

namespace Application\Form\Chargens\Traits;

use Application\Form\Chargens\DuplicationScenarioForm;

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
     *
     * @return self
     */
    public function setFormChargensDuplicationScenario(DuplicationScenarioForm $formChargensDuplicationScenario)
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
        if (!empty($this->formChargensDuplicationScenario)) {
            return $this->formChargensDuplicationScenario;
        }

        return \Application::$container->get('FormElementManager')->get('ChargensDuplicationScenario');
    }
}