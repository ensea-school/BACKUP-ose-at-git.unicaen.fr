<?php

namespace Application\Form\TypeIntervention\Traits;

use Application\Form\TypeIntervention\TypeInterventionStatutSaisieForm;

/**
 * Description of TypeInterventionStatutSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionStatutSaisieFormAwareTrait
{
    protected ?TypeInterventionStatutSaisieForm $formTypeInterventionTypeInterventionStatutSaisie;



    /**
     * @param TypeInterventionStatutSaisieForm|null $formTypeInterventionTypeInterventionStatutSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionTypeInterventionStatutSaisie( ?TypeInterventionStatutSaisieForm $formTypeInterventionTypeInterventionStatutSaisie )
    {
        $this->formTypeInterventionTypeInterventionStatutSaisie = $formTypeInterventionTypeInterventionStatutSaisie;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionStatutSaisie(): ?TypeInterventionStatutSaisieForm
    {
        if (!$this->formTypeInterventionTypeInterventionStatutSaisie){
            $this->formTypeInterventionTypeInterventionStatutSaisie = \Application::$container->get('FormElementManager')->get(TypeInterventionStatutSaisieForm::class);
        }

        return $this->formTypeInterventionTypeInterventionStatutSaisie;
    }
}