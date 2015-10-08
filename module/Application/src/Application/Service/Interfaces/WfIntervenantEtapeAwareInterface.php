<?php

namespace Application\Service\Interfaces;

use Application\Service\WfIntervenantEtape;
use RuntimeException;

/**
 * Description of WfIntervenantEtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface WfIntervenantEtapeAwareInterface
{
    /**
     * @param WfIntervenantEtape $serviceWfIntervenantEtape
     * @return self
     */
    public function setServiceWfIntervenantEtape( WfIntervenantEtape $serviceWfIntervenantEtape );



    /**
     * @return WfIntervenantEtapeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceWfIntervenantEtape();
}