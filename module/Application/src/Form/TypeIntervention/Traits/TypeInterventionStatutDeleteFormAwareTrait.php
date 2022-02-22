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
    /**
     * @var TypeInterventionStatutDeleteForm
     */
    private $formTypeInterventionStatutDelete;



    /**
     * @param TypeInterventionStatutSaisieForm $formTypeInterventionStatutDelete
     *
     * @return self
     */
    public function setFormTypeInterventionStatutDelete(TypeInterventionStatutDeleteForm $formTypeInterventionStatutDelete)
    {
        $this->formTypeInterventionStatutDelete = $formTypeInterventionStatutDelete;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeInterventionStatutDeleteForm
     */
    public function getFormTypeInterventionStatutDelete()
    {
        if (!empty($this->formTypeInterventionStatutDelete)) {
            return $this->formTypeInterventionStatutDelete;
        }

        return \Application::$container->get('FormElementManager')->get(TypeInterventionStatutDeleteForm::class);
    }
}