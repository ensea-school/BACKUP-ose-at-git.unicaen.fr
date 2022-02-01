<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\Intervenant;

/**
 * Description of IntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantAwareTrait
{
    protected ?Intervenant $intervenant;



    /**
     * @param Intervenant|null $intervenant
     *
     * @return self
     */
    public function setIntervenant( ?Intervenant $intervenant )
    {
        $this->intervenant = $intervenant;

        return $this;
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }
}