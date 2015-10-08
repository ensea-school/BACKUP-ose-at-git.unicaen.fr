<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\IntervenantPermanent;

/**
 * Description of IntervenantPermanentAwareInterface
 *
 * @author UnicaenCode
 */
interface IntervenantPermanentAwareInterface
{
    /**
     * @param IntervenantPermanent $intervenantPermanent
     * @return self
     */
    public function setIntervenantPermanent( IntervenantPermanent $intervenantPermanent = null );



    /**
     * @return IntervenantPermanent
     */
    public function getIntervenantPermanent();
}