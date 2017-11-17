<?php

namespace Application\Service\Traits;

use Application\Service\PilotageService;

/**
 * Description of PilotageServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait PilotageServiceAwareTrait
{
    /**
     * @var PilotageService
     */
    private $servicePilotage;



    /**
     * @param PilotageService $servicePilotage
     *
     * @return self
     */
    public function setServicePilotage(PilotageService $servicePilotage)
    {
        $this->servicePilotage = $servicePilotage;

        return $this;
    }



    /**
     * @return PilotageService
     */
    public function getServicePilotage()
    {
        if (empty($this->servicePilotage)) {
            $this->servicePilotage = \Application::$container->get('ApplicationPilotage');
        }

        return $this->servicePilotage;
    }
}