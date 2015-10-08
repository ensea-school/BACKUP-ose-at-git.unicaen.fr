<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\WfIntervenantEtape;

/**
 * Description of WfIntervenantEtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface WfIntervenantEtapeAwareInterface
{
    /**
     * @param WfIntervenantEtape $wfIntervenantEtape
     * @return self
     */
    public function setWfIntervenantEtape( WfIntervenantEtape $wfIntervenantEtape = null );



    /**
     * @return WfIntervenantEtape
     */
    public function getWfIntervenantEtape();
}