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

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(DuplicationScenarioForm::class);
    }
}