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
    /**
     * @var WfEtape
     */
    private $wfEtape;





    /**
     * @param WfEtape $wfEtape
     * @return self
     */
    public function setWfEtape( WfEtape $wfEtape = null )
    {
        $this->wfEtape = $wfEtape;
        return $this;
    }



    /**
     * @return WfEtape
     */
    public function getWfEtape()
    {
        return $this->wfEtape;
    }
}