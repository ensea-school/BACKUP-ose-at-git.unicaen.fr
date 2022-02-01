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
    protected ?PilotageService $servicePilotage;



    /**
     * @param PilotageService|null $servicePilotage
     *
     * @return self
     */
    public function setServicePilotage( ?PilotageService $servicePilotage )
    {
        $this->servicePilotage = $servicePilotage;

        return $this;
    }



    public function getServicePilotage(): ?PilotageService
    {
        if (!$this->servicePilotage){
            $this->servicePilotage = \Application::$container->get(PilotageService::class);
        }

        return $this->servicePilotage;
    }
}