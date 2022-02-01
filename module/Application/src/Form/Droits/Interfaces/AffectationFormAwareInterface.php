<?php

namespace Application\Form\Droits\Interfaces;

use Application\Form\Droits\AffectationForm;

/**
 * Description of AffectationFormAwareInterface
 *
 * @author UnicaenCode
 */
interface AffectationFormAwareInterface
{
    /**
     * @param AffectationForm|null $formDroitsAffectation
     *
     * @return self
     */
    public function setFormDroitsAffectation( AffectationForm $formDroitsAffectation );



    public function getFormDroitsAffectation(): ?AffectationForm;
}