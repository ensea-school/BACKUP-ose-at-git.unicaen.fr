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
    public function setFormTypeInterventionnStructureSaisie(?TypeInterventionStructureSaisieForm $formTypeInterventionTypeInterventionStructureSaisie)
    {
        $this->formTypeInterventionTypeInterventionStructureSaisie = $formTypeInterventionTypeInterventionStructureSaisie;

        return $this;
    }


    public function getFormTypeInterventionStructureSaisie(): ?TypeInterventionStructureSaisieForm
    {
        if (!empty($this->formTypeInterventionTypeInterventionStructureSaisie)) {
            return $this->formTypeInterventionTypeInterventionStructureSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeInterventionStructureSaisieForm::class);
    }
}