<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\StatutIntervenant;

/**
 * Description of StatutIntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait StatutIntervenantAwareTrait
{
    /**
     * @var StatutIntervenant
     */
    private $statutIntervenant;





    /**
     * @param StatutIntervenant $statutIntervenant
     * @return self
     */
    public function setStatutIntervenant( StatutIntervenant $statutIntervenant = null )
    {
        $this->statutIntervenant = $statutIntervenant;
        return $this;
    }



    /**
     * @return StatutIntervenant
     */
    public function getStatutIntervenant()
    {
        return $this->statutIntervenant;
    }
}