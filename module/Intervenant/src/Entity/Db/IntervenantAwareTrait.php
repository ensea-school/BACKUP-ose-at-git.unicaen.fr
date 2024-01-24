<?php

namespace Intervenant\Entity\Db;

/**
 * Description of IntervenantAwareTrait
 *
 * @author UnicaenCode
 */
trait IntervenantAwareTrait
{
    protected ?Intervenant $intervenant = null;



    /**
     * @param Intervenant $intervenant
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