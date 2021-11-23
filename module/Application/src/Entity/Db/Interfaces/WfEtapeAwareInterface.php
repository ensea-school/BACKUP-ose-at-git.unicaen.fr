<?php

namespace Application\Entity\Db\Interfaces;

use Application\Entity\Db\WfEtape;

/**
 * Description of WfEtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface WfEtapeAwareInterface
{
    /**
     * @param WfEtape $wfEtape
     * @return self
     */
    public function setWfEtape( WfEtape $wfEtape = null );



    /**
     * @return WfEtape
     */
    public function getWfEtape();
}