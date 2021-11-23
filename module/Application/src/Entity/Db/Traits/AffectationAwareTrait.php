<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Affectation;

/**
 * Description of AffectationServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait AffectationAwareTrait
{
    /**
     * @var Affectation
     */
    private $affectation;





    /**
     * @param Affectation $affectation
     * @return self
     */
    public function setAffectation( Affectation $affectation = null )
    {
        $this->affectation = $affectation;
        return $this;
    }



    /**
     * @return Affectation
     */
    public function getAffectation()
    {
        return $this->affectation;
    }
}