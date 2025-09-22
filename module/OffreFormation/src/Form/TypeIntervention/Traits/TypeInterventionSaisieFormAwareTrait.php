<?php

namespace OffreFormation\Form\TypeIntervention\Traits;

use OffreFormation\Form\TypeIntervention\TypeInterventionSaisieForm;

/**
 * Description of TypeInterventionSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionSaisieFormAwareTrait
{
    protected ?TypeInterventionSaisieForm $formTypeInterventionTypeInterventionSaisie = null;



    /**
     * @param TypeInterventionSaisieForm $formTypeInterventionTypeInterventionSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionTypeInterventionSaisie(?TypeInterventionSaisieForm $formTypeInterventionTypeInterventionSaisie)
    {
        $this->formTypeInterventionTypeInterventionSaisie = $formTypeInterventionTypeInterventionSaisie;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionSaisie(): ?TypeInterventionSaisieForm
    {
        if (!empty($this->formTypeInterventionTypeInterventionSaisie)) {
            return $this->formTypeInterventionTypeInterventionSaisie;
        }

        return \Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeInterventionSaisieForm::class);
    }
}