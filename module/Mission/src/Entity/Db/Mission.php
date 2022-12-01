<?php

namespace Mission\Entity\Db;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Mission implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;

    protected ?int             $id              = null;

    protected ?TypeMission     $typeMission     = null;

    protected ?MissionTauxRemu $missionTauxRemu = null;

    protected ?\DateTime       $dateDebut       = null;

    protected ?\DateTime       $dateFin         = null;

    protected ?string          $description     = null;

    private Collection         $etudiants;



    public function __construct()
    {
        $this->etudiants = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
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



    public function getMissionTauxRemu(): ?MissionTauxRemu
    {
        return $this->missionTauxRemu;
    }



    public function setMissionTauxRemu(?MissionTauxRemu $missionTauxRemu): self
    {
        $this->missionTauxRemu = $missionTauxRemu;

        return $this;
    }



    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function setDateDebut(?\DateTime $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }



    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }



    public function setDateFin(?\DateTime $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }



    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }



    /**
     * @return Collection|Intervenant[]
     */
    public function getEtudiants(): Collection
    {
        return $this->etudiants;
    }



    public function addEtudiant(Intervenant $intervenant): self
    {
        $this->etudiants[] = $intervenant;

        return $this;
    }



    public function removeEtudiant(Intervenant $intervenant): self
    {
        $this->etudiants->removeElement($intervenant);

        return $this;
    }
}
