<?php

namespace Application\Service\Interfaces;

use Application\Service\Etape;
use RuntimeException;

/**
 * Description of EtapeAwareInterface
 *
 * @author UnicaenCode
 */
interface EtapeAwareInterface
{
    /**
     * @param Etape $serviceEtape
     * @return self
     */
    public function setServiceEtape( Etape $serviceEtape );



    /**
     * @return EtapeAwareInterface
     * @throws RuntimeException
     */
    public function getServiceEtape();
}