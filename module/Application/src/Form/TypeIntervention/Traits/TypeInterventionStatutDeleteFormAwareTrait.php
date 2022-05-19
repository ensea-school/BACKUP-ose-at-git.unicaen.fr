<?php

namespace Application\Form\TypeIntervention\Traits;

use Application\Form\TypeIntervention\TypeInterventionStatutDeleteForm;

/**
 * Description of TypeInterventionStatutDeleteFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutDeleteFormAwareTrait
{
    protected ?TypeInterventionStatutDeleteForm $formTypeInterventionTypeInterventionStatutDelete = null;



    /**
     * @param TypeInterventionStatutDeleteForm $formTypeInterventionTypeInterventionStatutDelete
     *
     * @return self
     */
    public function setFormTypeInterventionTypeInterventionStatutDelete(?TypeInterventionStatutDeleteForm $formTypeInterventionTypeInterventionStatutDelete)
    {
        $this->formTypeInterventionTypeInterventionStatutDelete = $formTypeInterventionTypeInterventionStatutDelete;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionStatutDelete(): ?TypeInterventionStatutDeleteForm
    {
        if (!empty($this->formTypeInterventionTypeInterventionStatutDelete)) {
            return $this->formTypeInterventionTypeInterventionStatutDelete;
        }

        return \Application::$container->get('FormElementManager')->get(TypeInterventionStatutDeleteForm::class);
    }
}