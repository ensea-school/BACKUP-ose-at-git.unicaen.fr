<?php

namespace Application\Form\TypeIntervention\Traits;

use Application\Form\TypeIntervention\TypeInterventionStructureSaisieForm;

/**
 * Description of TypeInterventionStructureSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStructureSaisieFormAwareTrait
{
    protected ?TypeInterventionStructureSaisieForm $formTypeInterventionTypeInterventionStructureSaisie = null;



    /**
     * @param TypeInterventionStructureSaisieForm $formTypeInterventionTypeInterventionStructureSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionTypeInterventionStructureSaisie( ?TypeInterventionStructureSaisieForm $formTypeInterventionTypeInterventionStructureSaisie )
    {
        $this->formTypeInterventionTypeInterventionStructureSaisie = $formTypeInterventionTypeInterventionStructureSaisie;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionStructureSaisie(): ?TypeInterventionStructureSaisieForm
    {
        if (empty($this->formTypeInterventionTypeInterventionStructureSaisie)){
            $this->formTypeInterventionTypeInterventionStructureSaisie = \Application::$container->get('FormElementManager')->get(TypeInterventionStructureSaisieForm::class);
        }

        return $this->formTypeInterventionTypeInterventionStructureSaisie;
    }
}