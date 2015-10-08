<?php

namespace Application\Entity\Db\Traits;

use Application\Entity\Db\WfIntervenantEtape;

/**
 * Description of WfIntervenantEtapeAwareTrait
 *
 * @author UnicaenCode
 */
trait WfIntervenantEtapeAwareTrait
{
    /**
     * @var WfIntervenantEtape
     */
    private $wfIntervenantEtape;





    /**
     * @param WfIntervenantEtape $wfIntervenantEtape
     * @return self
     */
    public function setWfIntervenantEtape( WfIntervenantEtape $wfIntervenantEtape = null )
    {
        $this->wfIntervenantEtape = $wfIntervenantEtape;
        return $this;
    }



    /**
     * @return WfIntervenantEtape
     */
    public function getWfIntervenantEtape()
    {
        return $this->wfIntervenantEtape;
    }
}