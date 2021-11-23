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
    /**
     * @var DependanceForm
     */
    private $formWorkflowDependance;



    /**
     * @param DependanceForm $formWorkflowDependance
     *
     * @return self
     */
    public function setFormWorkflowDependance(DependanceForm $formWorkflowDependance)
    {
        $this->formWorkflowDependance = $formWorkflowDependance;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return DependanceForm
     * @throws RuntimeException
     */
    public function getFormWorkflowDependance()
    {
        if (!empty($this->formWorkflowDependance)) {
            return $this->formWorkflowDependance;
        }

        return \Application::$container->get('FormElementManager')->get(DependanceForm::class);
    }
}