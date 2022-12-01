<?php

namespace Mission\Entity\Db;

use Application\Interfaces\ParametreEntityInterface;
use Application\Traits\ParametreEntityTrait;

class TypeMission implements ParametreEntityInterface
{
    use ParametreEntityTrait;

    protected ?int             $id              = null;

    protected ?string          $code            = null;

    protected ?string          $libelle         = null;

    protected ?MissionTauxRemu $missionTauxRemu = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): MissionTauxRemu
    {
        $this->code = $code;

        return $this;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): MissionTauxRemu
    {
        $this->libelle = $libelle;

        return $this;
    }



    public function getMissionTauxRemu(): ?MissionTauxRemu
    {
        return $this->missionTauxRemu;
    }



    public function setMissionTauxRemu(?MissionTauxRemu $missionTauxRemu): TypeMission
    {
        $this->missionTauxRemu = $missionTauxRemu;

        return $this;
    }



    public function __toString(): string
    {
        return $this->getLibelle();
    }
}
