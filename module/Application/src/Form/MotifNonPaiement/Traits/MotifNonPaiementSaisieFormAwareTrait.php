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
    protected ?MotifNonPaiementSaisieForm $formMotifNonPaiementMotifNonPaiementSaisie;



    /**
     * @param MotifNonPaiementSaisieForm|null $formMotifNonPaiementMotifNonPaiementSaisie
     *
     * @return self
     */
    public function setFormMotifNonPaiementMotifNonPaiementSaisie( ?MotifNonPaiementSaisieForm $formMotifNonPaiementMotifNonPaiementSaisie )
    {
        $this->formMotifNonPaiementMotifNonPaiementSaisie = $formMotifNonPaiementMotifNonPaiementSaisie;

        return $this;
    }



    public function getFormMotifNonPaiementMotifNonPaiementSaisie(): ?MotifNonPaiementSaisieForm
    {
        if (!$this->formMotifNonPaiementMotifNonPaiementSaisie){
            $this->formMotifNonPaiementMotifNonPaiementSaisie = \Application::$container->get('FormElementManager')->get(MotifNonPaiementSaisieForm::class);
        }

        return $this->formMotifNonPaiementMotifNonPaiementSaisie;
    }
}