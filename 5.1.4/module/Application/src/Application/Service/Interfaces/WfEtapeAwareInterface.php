<?php

namespace Application\Service\Interfaces;

use Application\Service\WfEtape;
use RuntimeException;

/**
 * Description of WfEtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface WfEtapeAwareInterface
{
    /**
     * @param WfEtape $serviceWfEtape
     * @return self
     */
    public function setServiceWfEtape( WfEtape $serviceWfEtape );



    /**
     * @return WfEtapeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceWfEtape();
}