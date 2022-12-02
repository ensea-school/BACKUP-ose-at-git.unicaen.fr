<?php

namespace Mission\Entity\Db;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Mission implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;

    protected ?int             $id              = null;

    protected ?TypeMission     $typeMission     = null;

    protected ?MissionTauxRemu $missionTauxRemu = null;

    protected ?\DateTime       $dateDebut       = null;

    protected ?\DateTime       $dateFin         = null;

    protected ?string          $description     = null;

    protected bool             $autoValidation  = false;

    private Collection         $etudiants;

    private Collection         $validations;



    public function __construct()
    {
        $this->etudiants   = new ArrayCollection();
        $this->validations = new ArrayCollection();
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
     * @return bool
     */
    public function isAutoValidation(): bool
    {
        return $this->autoValidation;
    }



    /**
     * @param bool $autoValidation
     *
     * @return Mission
     */
    public function setAutoValidation(bool $autoValidation): Mission
    {
        $this->autoValidation = $autoValidation;

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



    /**
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
    }



    public function addValidation(Validation $validation): self
    {
        $this->validations[] = $validation;

        return $this;
    }



    public function removeValidation(Validation $validation): self
    {
        $this->validations->removeElement($validation);

        return $this;
    }



    public function isValide(): bool
    {
        if ($this->isAutoValidation()) return true;

        if ($validations = $this->getValidation()) {
            foreach ($validations as $validation) {
                if ($validation->estNonHistorise()) return true;
            }
        }

        return false;
    }
}
