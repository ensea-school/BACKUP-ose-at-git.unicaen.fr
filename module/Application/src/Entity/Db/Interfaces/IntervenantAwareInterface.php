<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\Intervenant;

/**
 * Description of IntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface IntervenantAwareInterface
{
    /**
     * @param Intervenant $intervenant
     * @return self
     */
    public function setIntervenant( Intervenant $intervenant = null );



    /**
     * @return Intervenant
     */
    public function getIntervenant();
}