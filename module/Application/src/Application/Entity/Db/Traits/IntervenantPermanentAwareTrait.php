<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\IntervenantPermanent;

/**
 * Description of IntervenantPermanentAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantPermanentAwareTrait
{
    /**
     * @var IntervenantPermanent
     */
    private $intervenantPermanent;





    /**
     * @param IntervenantPermanent $intervenantPermanent
     * @return self
     */
    public function setIntervenantPermanent( IntervenantPermanent $intervenantPermanent = null )
    {
        $this->intervenantPermanent = $intervenantPermanent;
        return $this;
    }



    /**
     * @return IntervenantPermanent
     */
    public function getIntervenantPermanent()
    {
        return $this->intervenantPermanent;
    }
}