<?php

namespace Mission\Entity\Db;

use Application\Entity\Db\Traits\ContratAwareTrait;
use Application\Entity\Db\Traits\IntervenantAwareTrait;
use Application\Entity\Db\TypeValidation;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Enseignement\Entity\Db\VolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;

class VolumeHoraireMission implements HistoriqueAwareInterface, ImportAwareInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ContratAwareTrait;

    protected ?int       $id             = null;

    protected ?Mission   $mission        = null;

    protected float      $heures         = 0;

    protected bool       $autoValidation = false;

    protected ?\DateTime $horaireDebut   = null;

    protected ?\DateTime $horaireFin     = null;

    protected bool       $nocturne       = false;

    private Collection   $validations;



    public function __construct()
    {
        $this->validations = new ArrayCollection();
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    public function setMission(?Mission $mission): VolumeHoraireMission
    {
        $this->mission = $mission;

        return $this;
    }



    public function getHeures(): float
    {
        return $this->heures;
    }



    public function setHeures(float $heures): VolumeHoraireMission
    {
        $this->heures = $heures;

        return $this;
    }



    public function isAutoValidation(): bool
    {
        return $this->autoValidation;
    }



    public function setAutoValidation(bool $autoValidation): VolumeHoraireMission
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    public function getHoraireDebut(): ?\DateTime
    {
        return $this->horaireDebut;
    }



    public function setHoraireDebut(?\DateTime $horaireDebut): VolumeHoraireMission
    {
        $this->horaireDebut = $horaireDebut;

        return $this;
    }



    public function getHoraireFin(): ?\DateTime
    {
        return $this->horaireFin;
    }



    public function setHoraireFin(?\DateTime $horaireFin): VolumeHoraireMission
    {
        $this->horaireFin = $horaireFin;

        return $this;
    }



    public function isNocturne(): bool
    {
        return $this->nocturne;
    }



    public function setNocturne(bool $nocturne): VolumeHoraireMission
    {
        $this->nocturne = $nocturne;

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
}
