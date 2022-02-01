<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Affectation;

/**
 * Description of AffectationAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationAwareTrait
{
    protected ?Affectation $affectation;



    /**
     * @param Affectation|null $affectation
     *
     * @return self
     */
    public function setAffectation( ?Affectation $affectation )
    {
        $this->affectation = $affectation;

        return $this;
    }



    public function getAffectation(): ?Affectation
    {
        return $this->affectation;
    }
}