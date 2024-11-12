<?php

namespace Application\Form\Droits\Traits;

use Application\Form\Droits\AffectationForm;

/**
 * Description of AffectationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationFormAwareTrait
{
    protected ?AffectationForm $formDroitsAffectation = null;



    /**
     * @param AffectationForm $formDroitsAffectation
     *
     * @return self
     */
    public function setFormDroitsAffectation(?AffectationForm $formDroitsAffectation)
    {
        $this->formDroitsAffectation = $formDroitsAffectation;

        return $this;
    }



    public function getFormDroitsAffectation(): ?AffectationForm
    {
        if (!empty($this->formDroitsAffectation)) {
            return $this->formDroitsAffectation;
        }

        return \AppAdmin::container()->get('FormElementManager')->get(AffectationForm::class);
    }
}