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
    protected ?TypeInterventionStructureSaisieForm $formTypeInterventionTypeInterventionStructureSaisie;



    /**
     * @param TypeInterventionStructureSaisieForm|null $formTypeInterventionTypeInterventionStructureSaisie
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
        if (!$this->formTypeInterventionTypeInterventionStructureSaisie){
            $this->formTypeInterventionTypeInterventionStructureSaisie = \Application::$container->get('FormElementManager')->get(TypeInterventionStructureSaisieForm::class);
        }

        return $this->formTypeInterventionTypeInterventionStructureSaisie;
    }
}