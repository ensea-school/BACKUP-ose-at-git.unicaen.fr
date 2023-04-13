<?php

namespace Mission\Entity\Db;


/**
 * Description of TypeMissionAwareTrait
 *
 * @author UnicaenCode
 */
trait TypeMissionAwareTrait
{
    protected ?TypeMission $typeMission = null;



    /**
     * @param TypeMission $typeMission
     *
     * @return self
     */
    public function setTypeMission(?TypeMission $typeMission)
    {
        $this->typeMission = $typeMission;

        return $this;
    }



    public function getTypeMission(): ?TypeMission
    {
        return $this->typeMission;
    }
}