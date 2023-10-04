<?php

namespace Mission\Entity\Db;

use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\CentreCout;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class CentreCoutTypeMission implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    protected ?int         $id          = null;

    protected ?TypeMission $typeMission = null;

    protected ?CentreCout  $centreCouts = null;

    protected ?Structure   $structure   = null;



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function setStructure(?Structure $structure): void
    {
        $this->structure = $structure;
    }



    /**
     * @param CentreCout|null $centreCouts
     */
    public function setCentreCouts(?CentreCout $centreCouts): void
    {
        $this->centreCouts = $centreCouts;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getCentreCouts(): CentreCout
    {
        return $this->centreCouts;
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
