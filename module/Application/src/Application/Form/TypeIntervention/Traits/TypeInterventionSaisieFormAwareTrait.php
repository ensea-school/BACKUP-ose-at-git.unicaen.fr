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
    /**
     * @var TypeInterventionSaisieForm
     */
    private $formTypeInterventionSaisie;



    /**
     * @param TypeInterventionSaisieForm $formTypeInterventionSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionSaisie(TypeInterventionSaisieForm $formTypeInterventionSaisie)
    {
        $this->formTypeInterventionSaisie = $formTypeInterventionSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeInterventionSaisieForm
     */
    public function getFormTypeInterventionSaisie()
    {
        if (!empty($this->formTypeInterventionSaisie)) {
            return $this->formTypeInterventionSaisie;
        }

        return \Application::$container->get('FormElementManager')->get('TypeInterventionSaisie');
    }
}
