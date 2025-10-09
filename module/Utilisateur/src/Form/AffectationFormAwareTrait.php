<?php

namespace Utilisateur\Form;

use Utilisateur\Form\AffectationForm;

/**
 * Description of AffectationFormAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationFormAwareTrait
{
    protected ?AffectationForm $formDroitsAffectation = null;



    /**
     * @param \Utilisateur\Form\AffectationForm $formDroitsAffectation
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

        return \Unicaen\Framework\Application\Application::getInstance()->container()->get('FormElementManager')->get(AffectationForm::class);
    }
}