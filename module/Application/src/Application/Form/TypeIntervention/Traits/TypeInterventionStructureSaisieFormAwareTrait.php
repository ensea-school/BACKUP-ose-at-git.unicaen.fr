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
    /**
     * @var TypeInterventionStructureSaisieForm
     */
    private $formTypeInterventionStructureSaisie;



    /**
     * @param TypeInterventionStructureSaisieForm $formTypeInterventionStructureSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionStructureSaisie(TypeInterventionStructureSaisieForm $formTypeInterventionStructureSaisie)
    {
        $this->formTypeInterventionStructureSaisie = $formTypeInterventionStructureSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeInterventionStructureSaisieForm
     */
    public function getFormTypeInterventionStructureSaisie()
    {
        if (!empty($this->formTypeInterventionStructureSaisie)) {
            return $this->formTypeInterventionStructureSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeInterventionStructureSaisieForm::class);
    }
}
