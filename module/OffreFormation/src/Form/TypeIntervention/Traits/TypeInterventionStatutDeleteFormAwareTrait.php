<?php

namespace OffreFormation\Form\TypeIntervention\Traits;

use OffreFormation\Form\TypeIntervention\TypeInterventionStatutDeleteForm;

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

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeInterventionStatutDeleteForm::class);
    }
}