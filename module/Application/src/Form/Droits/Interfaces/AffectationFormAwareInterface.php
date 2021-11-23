<?php

namespace Application\Form\Droits\Interfaces;

use Application\Form\Droits\AffectationForm;
use RuntimeException;

/**
 * Description of AffectationFormAwareInterface
 *
 * @author UnicaenCode
 */
interface AffectationFormAwareInterface
{
    /**
     * @param AffectationForm $formDroitsAffectation
     * @return self
     */
    public function setFormDroitsAffectation( AffectationForm $formDroitsAffectation );



    /**
     * @return AffectationFormAwareInterface
     * @throws RuntimeException
     */
    public function getFormDroitsAffectation();
}