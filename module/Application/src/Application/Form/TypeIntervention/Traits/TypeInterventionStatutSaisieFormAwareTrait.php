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
    /**
     * @var TypeInterventionStatutSaisieForm
     */
    private $formTypeInterventionStatutSaisie;



    /**
     * @param TypeInterventionStatutSaisieForm $formTypeInterventionStatutSaisie
     *
     * @return self
     */
    public function setFormTypeInterventionStatutSaisie(TypeInterventionStatutSaisieForm $formTypeInterventionStatutSaisie)
    {
        $this->formTypeInterventionStatutSaisie = $formTypeInterventionStatutSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return TypeInterventionStatutSaisieForm
     */
    public function getFormTypeInterventionStatutSaisie()
    {
        if (!empty($this->formTypeInterventionStatutSaisie)) {
            return $this->formTypeInterventionStatutSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(TypeInterventionStatutSaisieForm::class);
    }
}