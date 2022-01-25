<?php

namespace Application\Form\StatutIntervenant\Traits;

use Application\Form\StatutIntervenant\StatutSaisieForm;

/**
 * Description of StatutIntervenantSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutSaisieFormAwareTrait
{
    /**
     * @var StatutSaisieForm
     */
    private $formStatutIntervenantSaisie;



    /**
     * @param StatutSaisieForm $formStatutIntervenantSaisie
     *
     * @return self
     */
    public function setFormStatutIntervenantSaisie(StatutSaisieForm $formStatutIntervenantSaisie)
    {
        $this->formStatutIntervenantSaisie = $formStatutIntervenantSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return StatutSaisieForm
     */
    public function getFormStatutIntervenantSaisie()
    {
        if (!empty($this->formStatutIntervenantSaisie)) {
            return $this->formStatutIntervenantSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(StatutSaisieForm::class);
    }
}

