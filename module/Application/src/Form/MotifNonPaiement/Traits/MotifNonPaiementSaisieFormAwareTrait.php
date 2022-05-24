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
    protected ?MotifNonPaiementSaisieForm $formMotifNonPaiementMotifNonPaiementSaisie = null;



    /**
     * @param MotifNonPaiementSaisieForm $formMotifNonPaiementMotifNonPaiementSaisie
     *
     * @return self
     */
    public function setFormMotifNonPaiementMotifNonPaiementSaisie(?MotifNonPaiementSaisieForm $formMotifNonPaiementMotifNonPaiementSaisie)
    {
        $this->formMotifNonPaiementMotifNonPaiementSaisie = $formMotifNonPaiementMotifNonPaiementSaisie;

        return $this;
    }



    public function getFormMotifNonPaiementMotifNonPaiementSaisie(): ?MotifNonPaiementSaisieForm
    {
        if (!empty($this->formMotifNonPaiementMotifNonPaiementSaisie)) {
            return $this->formMotifNonPaiementMotifNonPaiementSaisie;
        }

        return \Application::$container->get('FormElementManager')->get(MotifNonPaiementSaisieForm::class);
    }
}