<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\WfEtapeDep;

/**
 * Description of WfEtapeDepAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeDepAwareTrait
{
    protected ?WfEtapeDep $wfEtapeDep = null;



    /**
     * @param WfEtapeDep $wfEtapeDep
     *
     * @return self
     */
    public function setWfEtapeDep( ?WfEtapeDep $wfEtapeDep )
    {
        $this->wfEtapeDep = $wfEtapeDep;

        return $this;
    }



    public function getWfEtapeDep(): ?WfEtapeDep
    {
        return $this->wfEtapeDep;
    }
}