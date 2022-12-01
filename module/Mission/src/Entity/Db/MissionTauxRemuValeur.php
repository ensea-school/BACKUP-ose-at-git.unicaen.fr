<?php

namespace Mission\Entity\Db;

class MissionTauxRemuValeur
{
    protected ?int             $id              = null;

    protected ?\DateTime       $dateEffet       = null;

    protected ?MissionTauxRemu $missionTauxRemu = null;

    protected float            $valeur          = 0;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDateEffet(): ?\DateTime
    {
        return $this->dateEffet;
    }



    public function setDateEffet(?\DateTime $dateEffet): MissionTauxRemu
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }



    public function getMissionTauxRemu(): ?MissionTauxRemu
    {
        return $this->missionTauxRemu;
    }



    public function setMissionTauxRemu(?MissionTauxRemu $missionTauxRemu): MissionTauxRemu
    {
        $this->missionTauxRemu = $missionTauxRemu;

        return $this;
    }



    public function getValeur(): float
    {
        return $this->valeur;
    }



    public function setValeur(float $valeur): MissionTauxRemu
    {
        $this->valeur = $valeur;

        return $this;
    }
}
