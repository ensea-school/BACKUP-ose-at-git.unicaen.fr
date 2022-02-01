<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\WfDepBloquante;

/**
 * Description of WfDepBloquanteAwareTrait
 *
 * @author UnicaenCode
 */
trait WfDepBloquanteAwareTrait
{
    protected ?WfDepBloquante $wfDepBloquante;



    /**
     * @param WfDepBloquante|null $wfDepBloquante
     *
     * @return self
     */
    public function setWfDepBloquante( ?WfDepBloquante $wfDepBloquante )
    {
        $this->wfDepBloquante = $wfDepBloquante;

        return $this;
    }



    public function getWfDepBloquante(): ?WfDepBloquante
    {
        return $this->wfDepBloquante;
    }
}