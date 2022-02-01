<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\WfEtape;

/**
 * Description of WfEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait WfEtapeAwareTrait
{
    protected ?WfEtape $wfEtape = null;



    /**
     * @param WfEtape $wfEtape
     *
     * @return self
     */
    public function setWfEtape( ?WfEtape $wfEtape )
    {
        $this->wfEtape = $wfEtape;

        return $this;
    }



    public function getWfEtape(): ?WfEtape
    {
        return $this->wfEtape;
    }
}