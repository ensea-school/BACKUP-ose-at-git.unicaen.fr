<?php

namespace Workflow\Form;

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

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(DependanceForm::class);
    }
}