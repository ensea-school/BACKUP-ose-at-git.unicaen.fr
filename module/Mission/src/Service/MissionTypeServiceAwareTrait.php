<?php

namespace Mission\Service;


/**
 * Description of MissionTypeServiceAwareTrait
 *
 * @author UnicaenCode
 */
trait MissionTypeServiceAwareTrait
{
    protected ?MissionTypeService $serviceMissionType = null;



    /**
     * @param MissionTypeService $serviceMissionType
     *
     * @return self
     */
    public function setServiceMissionType(?MissionTypeService $serviceMissionType)
    {
        $this->serviceMissionType = $serviceMissionType;

        return $this;
    }



    public function getServiceMissionType(): ?MissionTypeService
    {
        if (empty($this->serviceMissionType)) {
            $this->serviceMissionType = \AppAdmin::container()->get(MissionTypeService::class);
        }

        return $this->serviceMissionType;
    }
}

