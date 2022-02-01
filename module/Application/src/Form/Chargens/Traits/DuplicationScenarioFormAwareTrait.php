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
    protected ?DuplicationScenarioForm $formChargensDuplicationScenario;



    /**
     * @param DuplicationScenarioForm|null $formChargensDuplicationScenario
     *
     * @return self
     */
    public function setFormChargensDuplicationScenario( ?DuplicationScenarioForm $formChargensDuplicationScenario )
    {
        $this->formChargensDuplicationScenario = $formChargensDuplicationScenario;

        return $this;
    }



    public function getFormChargensDuplicationScenario(): ?DuplicationScenarioForm
    {
        if (!$this->formChargensDuplicationScenario){
            $this->formChargensDuplicationScenario = \Application::$container->get('FormElementManager')->get(DuplicationScenarioForm::class);
        }

        return $this->formChargensDuplicationScenario;
    }
}