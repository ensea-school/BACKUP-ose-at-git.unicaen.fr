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
    protected ?DependanceForm $formWorkflowDependance;



    /**
     * @param DependanceForm|null $formWorkflowDependance
     *
     * @return self
     */
    public function setFormWorkflowDependance( ?DependanceForm $formWorkflowDependance )
    {
        $this->formWorkflowDependance = $formWorkflowDependance;

        return $this;
    }



    public function getFormWorkflowDependance(): ?DependanceForm
    {
        if (!$this->formWorkflowDependance){
            $this->formWorkflowDependance = \Application::$container->get('FormElementManager')->get(DependanceForm::class);
        }

        return $this->formWorkflowDependance;
    }
}