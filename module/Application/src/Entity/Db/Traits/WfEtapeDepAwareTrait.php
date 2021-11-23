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
    /**
     * @var WfEtapeDep
     */
    private $wfEtapeDep;





    /**
     * @param WfEtapeDep $wfEtapeDep
     * @return self
     */
    public function setWfEtapeDep( WfEtapeDep $wfEtapeDep = null )
    {
        $this->wfEtapeDep = $wfEtapeDep;
        return $this;
    }



    /**
     * @return WfEtapeDep
     */
    public function getWfEtapeDep()
    {
        return $this->wfEtapeDep;
    }
}