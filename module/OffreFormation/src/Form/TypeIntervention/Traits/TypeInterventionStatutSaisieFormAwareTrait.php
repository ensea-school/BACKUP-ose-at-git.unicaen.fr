<?php

namespace OffreFormation\Form\TypeIntervention\Traits;

use OffreFormation\Form\TypeIntervention\TypeInterventionStatutSaisieForm;

/**
 * Description of TypeInterventionStatutSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutSaisieFormAwareTrait
{
    protected ?TypeInterventionStatutSaisieForm $formTypeInterventionTypeInterventionStatutSaisie = null;



    /**
     * @param TypeInterventionStatutSaisieForm $formTypeInterventionTypeInterventionStatutSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionTypeInterventionStatutSaisie(?TypeInterventionStatutSaisieForm $formTypeInterventionTypeInterventionStatutSaisie)
    {
        $this->formTypeInterventionTypeInterventionStatutSaisie = $formTypeInterventionTypeInterventionStatutSaisie;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionStatutSaisie(): ?TypeInterventionStatutSaisieForm
    {
        if (!empty($this->formTypeInterventionTypeInterventionStatutSaisie)) {
            return $this->formTypeInterventionTypeInterventionStatutSaisie;
        }

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(TypeInterventionStatutSaisieForm::class);
    }
}