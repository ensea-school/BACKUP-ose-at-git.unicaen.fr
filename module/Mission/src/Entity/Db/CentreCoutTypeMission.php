<?php

namespace Mission\Entity\Db;

use Paiement\Entity\Db\CentreCout;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class CentreCoutTypeMission implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    protected ?int $id = null;


    protected ?CentreCout $centreCout = null;

    protected ?TypeMission $typeMission = null;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCentreCout(): ?CentreCout
    {
        return $this->centreCout;
    }



    public function setCentreCout(?CentreCout $centreCout): self
    {
        $this->centreCout = $centreCout;

        return $this;
    }



    public function getTypeMission(): ?TypeMission
    {
        return $this->typeMission;
    }



    public function setTypeMission(?TypeMission $typeMission): self
    {
        $this->typeMission = $typeMission;

        return $this;
    }
}
