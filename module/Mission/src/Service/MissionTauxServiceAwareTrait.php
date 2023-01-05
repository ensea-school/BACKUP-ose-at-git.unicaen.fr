<?php

namespace Mission\Service;


/**
 * Description of MissionTauxServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionTauxServiceAwareTrait
{
    protected ?MissionTauxService $serviceMissionTaux = null;



    /**
     * @param MissionTauxService $serviceMissionTaux
     *
     * @return self
     */
    public function setServiceMissionTaux(?MissionTauxService $serviceMissionTaux)
    {
        $this->serviceMissionTaux = $serviceMissionTaux;

        return $this;
    }



    public function getServiceMissionTaux(): ?MissionTauxService
    {
        if (empty($this->serviceMissionTaux)) {
            $this->serviceMissionTaux = \Application::$container->get(MissionTauxService::class);
        }

        return $this->serviceMissionTaux;
    }
}

