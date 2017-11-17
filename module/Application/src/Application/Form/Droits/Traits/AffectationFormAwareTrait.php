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
    /**
     * @var AffectationForm
     */
    private $formDroitsAffectation;



    /**
     * @param AffectationForm $formDroitsAffectation
     *
     * @return self
     */
    public function setFormDroitsAffectation(AffectationForm $formDroitsAffectation)
    {
        $this->formDroitsAffectation = $formDroitsAffectation;

        return $this;
    }



    /**
     * Retourne un nouveau formulaire ou fieldset systématiquement, sauf si ce dernier a été fourni manuellement.
     *
     * @return AffectationForm
     */
    public function getFormDroitsAffectation()
    {
        if (!empty($this->formDroitsAffectation)) {
            return $this->formDroitsAffectation;
        }

        return \Application::$container->get('FormElementManager')->get('DroitsAffectationForm');
    }
}