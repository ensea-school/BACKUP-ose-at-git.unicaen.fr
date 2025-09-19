<?php

namespace Mission\Service;


/**
 * Description of MissionServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionServiceAwareTrait
{
    protected ?MissionService $serviceMission = null;



    /**
     * @param MissionService $serviceMission
     *
     * @return self
     */
    public function setServiceMission(?MissionService $serviceMission)
    {
        $this->serviceMission = $serviceMission;

        return $this;
    }



    public function getServiceMission(): ?MissionService
    {
        if (empty($this->serviceMission)) {
            $this->serviceMission = \Framework\Application\Application::getInstance()->container()->get(MissionService::class);
        }

        return $this->serviceMission;
    }
}