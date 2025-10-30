<?php

namespace Mission\Entity\Db;

use Application\Interfaces\AxiosExtractor;
use Contrat\Entity\Db\Contrat;
use Contrat\Entity\Db\ContratAwareTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Plafond\Interfaces\PlafondDataInterface;
use Service\Entity\Db\TypeVolumeHoraireAwareTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenImport\Entity\Db\Interfaces\ImportAwareInterface;
use UnicaenImport\Entity\Db\Traits\ImportAwareTrait;
use Workflow\Entity\Db\Validation;

class VolumeHoraireMission implements HistoriqueAwareInterface, ImportAwareInterface, ResourceInterface, PlafondDataInterface
{
    use HistoriqueAwareTrait;
    use ImportAwareTrait;
    use TypeVolumeHoraireAwareTrait;
    use ContratAwareTrait;

    protected ?int $id = null;

    protected ?Mission $mission = null;

    protected float $heures = 0;

    protected bool $autoValidation = false;

    protected ?\DateTime $horaireDebut = null;

    protected ?\DateTime $horaireFin = null;

    protected bool $formation = false;

    protected ?string $description = null;

    private Collection $validations;

    private Collection $etatVolumeHoraire;

    protected ?Contrat $contrat = null;



    /**
     * @return Contrat|null
     */
    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }



    /**
     * @param Contrat|null $contrat
     */
    public function setContrat(?Contrat $contrat = null): void
    {
        $this->contrat = $contrat;
    }



    public function __construct()
    {
        $this->validations = new ArrayCollection();
    }



    public function getResourceId(): string
    {
        return self::class;
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
        $this->setHeuresFromHoraires();

        return $this;
    }



    public function getHoraireFin(): ?\DateTime
    {
        return $this->horaireFin;
    }



    public function setHoraireFin(?\DateTime $horaireFin): VolumeHoraireMission
    {
        $this->horaireFin = $horaireFin;
        $this->setHeuresFromHoraires();

        return $this;
    }



    public function getDate(): ?string
    {
        return $this->getHoraireDebut()?->format('Y-m-d');
    }



    public function setDate(?string $dateStr): self
    {
        if ($this->isValide() && $dateStr !== $this->getDate()) {
            throw new \Exception('La date ne peut pas être modifiée : des heures ont déjà été validées');
        }

        if (!$dateStr) {
            $dateStr = '0000-00-00';
        }
        $date = explode('-', $dateStr);

        $horaireDebut = $this->getHoraireDebut() ?: (new \DateTime)->setTime(0, 0, 0, 0);
        $horaireFin = $this->getHoraireFin() ?: (new \DateTime)->setTime(0, 0, 0, 0);

        $horaireDebut->setDate($date[0], $date[1], $date[2]);
        $horaireFin->setDate($date[0], $date[1], $date[2]);

        $this->setHoraireDebut($horaireDebut);
        $this->setHoraireFin($horaireFin);

        return $this;
    }



    public function getHeureDebut(): ?string
    {
        return $this->getHoraireDebut()?->format('H:i');
    }



    public function setHeureDebut(?string $heureStr): self
    {
        if (!$heureStr) {
            $heureStr = '00:00';
        }
        $heure = explode(':', $heureStr);

        $horaireDebut = clone($this->getHoraireDebut()) ?: new \DateTime();

        $horaireDebut->setTime($heure[0], $heure[1], 0);

        $this->setHoraireDebut($horaireDebut);

        return $this;
    }



    public function getHeureFin(): ?string
    {
        return $this->getHoraireFin()?->format('H:i');
    }



    public function setHeureFin(?string $heureStr): self
    {
        if (!$heureStr) {
            $heureStr = '00:00';
        }
        $heure = explode(':', $heureStr);

        $horaireFin = clone($this->getHoraireFin()) ?: new \DateTime();

        $horaireFin->setTime($heure[0], $heure[1], 0);

        $this->setHoraireFin($horaireFin);

        return $this;
    }



    public function setHeuresFromHoraires(): self
    {
        if ($this->horaireDebut instanceof \DateTime && $this->horaireFin instanceof \DateTime) {
            $ts = abs($this->horaireFin->getTimestamp() - $this->horaireDebut->getTimestamp());
            $ts = round($ts / 60); // en minutes

            $this->heures = round($ts / 60, 2); // en heures arrondies à 0.01 au cas où
        } else {
            $this->heures = 0;
        }

        return $this;
    }



    public function isFormation(): bool
    {
        return $this->formation;
    }



    public function setFormation(bool $formation): VolumeHoraireMission
    {
        $this->formation = $formation;

        return $this;
    }



    public function getDescription(): ?string
    {
        return $this->description;
    }



    public function setDescription(?string $description): VolumeHoraireMission
    {
        $this->description = $description;

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



    public function canEdit(): bool
    {
        return !$this->isValide();
    }



    public function canValider(): bool
    {
        return !$this->isValide();
    }



    public function canDevalider(): bool
    {
        if ($this->getTypeVolumeHoraire()->isPrevu()) {
            return $this->isValide() && !$this->getMission()->isValide();
        } else {
            return $this->isValide();
        }

    }



    public function canSupprimer(): bool
    {
        if ($this->getTypeVolumeHoraire()->isPrevu()) {
            return !$this->isValide() && !$this->getMission()->isValide();
        } else {
            return !$this->isValide();
        }
    }

}
