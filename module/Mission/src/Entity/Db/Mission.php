<?php

namespace Mission\Entity\Db;

use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\Traits\StructureAwareTrait;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Mission implements HistoriqueAwareInterface, ResourceInterface
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

    private Collection         $volumesHoraires;



    public function __construct()
    {
        $this->etudiants       = new ArrayCollection();
        $this->validations     = new ArrayCollection();
        $this->volumesHoraires = new ArrayCollection();
    }



    public function getResourceId()
    {
        return 'Mission';
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



    public function getHeures(): ?float
    {
        $heures = null;

        /** @var VolumeHoraireMission[] $vhs */
        $vhs = $this->volumesHoraires;
        foreach ($vhs as $vh) {
            if ($vh->estNonHistorise() && $vh->getTypeVolumeHoraire()->isPrevu()) {
                if ($heures === null) {
                    $heures = 0;
                }
                $heures += $vh->getHeures();
            }
        }

        return $heures;
    }



    public function getHeuresValidees(): ?float
    {
        $heures = null;

        /** @var VolumeHoraireMission[] $vhs */
        $vhs = $this->volumesHoraires;
        foreach ($vhs as $vh) {
            if ($vh->estNonHistorise() && $vh->getTypeVolumeHoraire()->isPrevu() && $vh->isValide()) {
                if ($heures === null) {
                    $heures = 0;
                }
                $heures += $vh->getHeures();
            }
        }

        return $heures;
    }



    public function setHeures(float $heures): self
    {
        $oldHeures = $this->getHeures() ?: 0;
        $newHeures = $heures - $oldHeures;

        if ($newHeures != 0) {

        }

        var_dump($newHeures);

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

        if ($validations = $this->getValidations()) {
            foreach ($validations as $validation) {
                if ($validation->estNonHistorise()) return true;
            }
        }

        return false;
    }



    public function getValidation(): ?Validation
    {
        if ($this->isAutoValidation()) {
            return new Validation();
        }

        if ($validations = $this->getValidations()) {
            foreach ($validations as $validation) {
                if ($validation->estNonHistorise()) return $validation;
            }
        }

        return null;
    }



    /**
     * @return Collection|VolumeHoraireMission[]
     */
    public function getVolumeHoraires(): Collection
    {
        return $this->volumesHoraires;
    }



    public function addVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $this->volumesHoraires[] = $volumeHoraireMission;

        return $this;
    }



    public function removeVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $this->volumesHoraires->removeElement($volumeHoraireMission);

        return $this;
    }



    public function hasContrat(): bool
    {
        /** @var VolumeHoraireMission[] $vhs */
        $vhs = $this->getVolumeHoraires();

        foreach ($vhs as $vh) {
            if ($vh->estNonHistorise() && $vh->getContrat() && $vh->getContrat()->estNonHistorise()) {
                return true;
            }
        }

        return false;
    }
}
