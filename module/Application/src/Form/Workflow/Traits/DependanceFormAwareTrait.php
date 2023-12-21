<?php

namespace Application\Form\Workflow\Traits;

use Application\Form\Workflow\DependanceForm;

/**
 * Description of DependanceFormAwareTrait
 *
 * @author UnicaenCode
 */
trait DependanceFormAwareTrait
{
    protected ?DependanceForm $formWorkflowDependance = null;



    /**
     * @param DependanceForm $formWorkflowDependance
     *
     * @return self
     */
    public function setFormWorkflowDependance(?DependanceForm $formWorkflowDependance)
    {
        $this->formWorkflowDependance = $formWorkflowDependance;

        return $this;
    }



    public function getFormWorkflowDependance(): ?DependanceForm
    {
        if (!empty($this->formWorkflowDependance)) {
            return $this->formWorkflowDependance;
        }

        return \OseAdmin::instance()->container()->get('FormElementManager')->get(DependanceForm::class);
    }
}