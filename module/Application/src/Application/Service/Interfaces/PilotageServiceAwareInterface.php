<?php

namespace Application\Service\Interfaces;

use Application\Service\PilotageService;
use RuntimeException;

/**
 * Description of PilotageServiceAwareInterface
 *
 * @author UnicaenCode
 */
interface PilotageServiceAwareInterface
{
    /**
     * @param PilotageService $servicePilotage
     * @return self
     */
    public function setServicePilotage( PilotageService $servicePilotage );



    /**
     * @return PilotageServiceAwareInterface
     * @throws RuntimeException
     */
    public function getServicePilotage();
}