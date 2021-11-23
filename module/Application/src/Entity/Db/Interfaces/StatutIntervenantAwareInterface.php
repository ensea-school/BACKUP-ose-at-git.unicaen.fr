<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\StatutIntervenant;

/**
 * Description of StatutIntervenantAwareInterface
 *
 * @author UnicaenCode
 */
interface StatutIntervenantAwareInterface
{
    /**
     * @param StatutIntervenant $statutIntervenant
     * @return self
     */
    public function setStatutIntervenant( StatutIntervenant $statutIntervenant = null );



    /**
     * @return StatutIntervenant
     */
    public function getStatutIntervenant();
}