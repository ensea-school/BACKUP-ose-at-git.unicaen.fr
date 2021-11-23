<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Intervenant;

/**
 * Description of IntervenantServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantAwareTrait
{
    /**
     * @var Intervenant
     */
    private $intervenant;





    /**
     * @param Intervenant $intervenant
     * @return self
     */
    public function setIntervenant( Intervenant $intervenant = null )
    {
        $this->intervenant = $intervenant;
        return $this;
    }



    /**
     * @return Intervenant
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }
}