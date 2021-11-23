<?php

namespace Application\Form\MotifNonPaiement\Traits;

use Application\Form\MotifNonPaiement\MotifNonPaiementSaisieForm;

/**
 * Description of MotifNonPaiementSaisieFormAwareTrait
 *
 * @author UnicaenCode
 */
trait MotifNonPaiementSaisieFormAwareTrait
{
    /**
     * @var MotifNonPaiementSaisieForm
     */
    private $formMotifNonPaiementSaisie;



    /**
     * @param MotifNonPaiementSaisieForm $formMotifNonPaiementSaisie
     *
     * @return self
     */
    public function setFormMotifNonPaiementSaisie(MotifNonPaiementSaisieForm $formMotifNonPaiementSaisie)
    {
        $this->formMotifNonPaiementSaisie = $formMotifNonPaiementSaisie;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return MotifNonPaiementSaisieForm
     */
    public function getFormMotifNonPaiementSaisie()
    {
        if (!empty($this->formMotifNonPaiementSaisie)) {
            return $this->formMotifNonPaiementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(MotifNonPaiementSaisieForm::class);
    }
}
