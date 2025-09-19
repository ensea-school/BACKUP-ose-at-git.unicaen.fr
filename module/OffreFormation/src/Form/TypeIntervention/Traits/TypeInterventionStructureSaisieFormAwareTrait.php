<?php

namespace OffreFormation\Form\TypeIntervention\Traits;

use OffreFormation\Form\TypeIntervention\TypeInterventionStructureSaisieForm;

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
    public function setFormTypeInterventionTypeInterventionStructureSaisie(?TypeInterventionStructureSaisieForm $formTypeInterventionTypeInterventionStructureSaisie)
    {
        $this->formTypeInterventionTypeInterventionStructureSaisie = $formTypeInterventionTypeInterventionStructureSaisie;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionStructureSaisie(): ?TypeInterventionStructureSaisieForm
    {
        if (!empty($this->formTypeInterventionTypeInterventionStructureSaisie)) {
            return $this->formTypeInterventionTypeInterventionStructureSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeInterventionStructureSaisieForm::class);
    }
}