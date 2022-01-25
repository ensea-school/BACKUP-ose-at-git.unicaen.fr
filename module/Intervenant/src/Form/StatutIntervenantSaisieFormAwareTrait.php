<?php

namespace Application\Form\StatutIntervenant\Traits;

use Application\Form\StatutIntervenant\StatutIntervenantSaisieForm;

/**
 * Description of StatutIntervenantSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantSaisieFormAwareTrait
{
    /**
     * @var StatutIntervenantSaisieForm
     */
    private $formStatutIntervenantSaisie;



    /**
     * @param StatutIntervenantSaisieForm $formStatutIntervenantSaisie
     *
     * @return self
     */
    public function setFormStatutIntervenantSaisie(StatutIntervenantSaisieForm $formStatutIntervenantSaisie)
    {
        $this->formStatutIntervenantSaisie = $formStatutIntervenantSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return StatutIntervenantSaisieForm
     */
    public function getFormStatutIntervenantSaisie()
    {
        if (!empty($this->formStatutIntervenantSaisie)) {
            return $this->formStatutIntervenantSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(StatutIntervenantSaisieForm::class);
    }
}

