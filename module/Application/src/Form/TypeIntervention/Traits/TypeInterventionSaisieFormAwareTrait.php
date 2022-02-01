<?php

namespace Application\Form\TypeIntervention\Traits;

use Application\Form\TypeIntervention\TypeInterventionSaisieForm;

/**
 * Description of TypeInterventionSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeInterventionSaisieFormAwareTrait
{
    protected ?TypeInterventionSaisieForm $formTypeInterventionTypeInterventionSaisie;



    /**
     * @param TypeInterventionSaisieForm|null $formTypeInterventionTypeInterventionSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionTypeInterventionSaisie( ?TypeInterventionSaisieForm $formTypeInterventionTypeInterventionSaisie )
    {
        $this->formTypeInterventionTypeInterventionSaisie = $formTypeInterventionTypeInterventionSaisie;

        return $this;
    }



    public function getFormTypeInterventionTypeInterventionSaisie(): ?TypeInterventionSaisieForm
    {
        if (!$this->formTypeInterventionTypeInterventionSaisie){
            $this->formTypeInterventionTypeInterventionSaisie = \Application::$container->get('FormElementManager')->get(TypeInterventionSaisieForm::class);
        }

        return $this->formTypeInterventionTypeInterventionSaisie;
    }
}