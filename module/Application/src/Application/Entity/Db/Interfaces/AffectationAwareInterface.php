<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Affectation;

/**
 * Description of AffectationAwareInterface
 *
 * @author UnicaenCode
 */
interface AffectationAwareInterface
{
    /**
     * @param Affectation $affectation
     * @return self
     */
    public function setAffectation( Affectation $affectation = null );



    /**
     * @return Affectation
     */
    public function getAffectation();
}