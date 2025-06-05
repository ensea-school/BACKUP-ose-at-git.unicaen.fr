<?php

namespace Chargens\Form;

use Application\Form\Chargens\DuplicationScenarioForm;

/**
 * Description of DuplicationScenarioFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DuplicationScenarioFormAwareTrait
{
    protected ?DuplicationScenarioForm $formChargensDuplicationScenario = null;



    /**
     * @param DuplicationScenarioForm $formChargensDuplicationScenario
     *
     * @return self
     */
    public function setFormChargensDuplicationScenario(?DuplicationScenarioForm $formChargensDuplicationScenario)
    {
        $this->formChargensDuplicationScenario = $formChargensDuplicationScenario;

        return $this;
    }



    public function getFormChargensDuplicationScenario(): ?DuplicationScenarioForm
    {
        if (!empty($this->formChargensDuplicationScenario)) {
            return $this->formChargensDuplicationScenario;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(DuplicationScenarioForm::class);
    }
}