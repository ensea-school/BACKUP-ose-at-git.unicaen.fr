<?php

namespace Mission\Entity\Db;

use Application\Constants;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\IntervenantAwareTrait;
use Laminas\Permissions\Acl\Resource\ResourceInterface;
use Lieu\Entity\Db\StructureAwareTrait;
use OffreFormation\Entity\Db\TypeHeures;
use Paiement\Entity\Db\CentreCout;
use Paiement\Entity\Db\DomaineFonctionnel;
use Paiement\Entity\Db\TauxRemu;
use Plafond\Interfaces\PlafondDataInterface;
use Service\Entity\Db\TypeVolumeHoraire;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenApp\Service\EntityManagerAwareInterface;
use UnicaenApp\Service\EntityManagerAwareTrait;
use Workflow\Entity\Db\Validation;

class Mission implements HistoriqueAwareInterface, ResourceInterface, EntityManagerAwareInterface, PlafondDataInterface
{
    use HistoriqueAwareTrait;
    use IntervenantAwareTrait;
    use StructureAwareTrait;
    use EntityManagerAwareTrait;

    protected ?int         $id              = null;
    protected ?TypeMission $typeMission     = null;
    protected ?TauxRemu    $tauxRemu        = null;
    protected ?TauxRemu    $tauxRemuMajore  = null;
    protected ?\DateTime   $dateDebut       = null;
    protected ?\DateTime   $dateFin         = null;
    protected ?float       $heuresFormation = null;
    protected ?string      $description     = null;
    protected ?string      $libelleMission  = null;
    protected ?string      $etudiantsSuivis = null;
    protected bool         $autoValidation  = false;
    private Collection     $etudiants;
    private Collection     $validations;
    private Collection     $volumesHoraires;
    private Collection     $miseEnPaiement;
    private Collection     $centreCout;
    private ?Prime         $prime           = null;
    private bool           $primeActive     = true;



    public function __construct()
    {
        $this->etudiants       = new ArrayCollection();
        $this->validations     = new ArrayCollection();
        $this->volumesHoraires = new ArrayCollection();
        $this->miseEnPaiement  = new ArrayCollection();
        $this->centreCout      = new ArrayCollection();
    }



    public function __toString(): string
    {
        return $this->getLibelleMission() ? $this->getLibelleCourt() : '';
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): Mission
    {
        $this->id = $id;
        return $this;
    }



    public function getLibelleCourt(): string
    {
        return $this->getTypeMission()->getLibelle() . ' (' . $this->getStructure()->getLibelleCourt() . ')';
    }



    public function getLibelle(): string
    {
        return $this->getTypeMission()->getLibelle()
            . ' / ' . $this->getLibelleMission()
            . '(du ' . $this->getDateDebut()->format(Constants::DATE_FORMAT)
            . ' au ' . $this->getDateFin()->format(Constants::DATE_FORMAT)
            . ')';
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



    public function getLibelleMission(): ?string
    {
        return $this->libelleMission;
    }



    public function setLibelleMission(?string $libelleMission): self
    {
        $this->libelleMission = $libelleMission;

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



    public function getResourceId(): string
    {
        return self::class;
    }



    public function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    public function setTauxRemu(?TauxRemu $tauxRemu): self
    {
        $this->tauxRemu = $tauxRemu;

        return $this;
    }



    public function getTauxRemuMajore(): ?TauxRemu
    {
        return $this->tauxRemuMajore;
    }



    public function setTauxRemuMajore(?TauxRemu $tauxRemuMajore): self
    {
        $this->tauxRemuMajore = $tauxRemuMajore;

        return $this;
    }



    public function getHeuresFormation(): ?float
    {
        return $this->heuresFormation;
    }



    public function setHeuresFormation(?float $heuresFormation): self
    {
        $this->heuresFormation = $heuresFormation;

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



    public function getEtudiantsSuivis(): ?string
    {
        return $this->etudiantsSuivis;
    }



    public function setEtudiantsSuivis(?string $etudiantsSuivis): self
    {
        $this->etudiantsSuivis = $etudiantsSuivis;

        return $this;
    }



    public function getHeuresValidees(): float
    {
        $heures = 0;

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



    public function isAutoValidation(): bool
    {
        return $this->autoValidation;
    }



    public function setAutoValidation(bool $autoValidation): self
    {
        $this->autoValidation = $autoValidation;

        return $this;
    }



    /**
     * @return Collection|Validation[]
     */
    public function getValidations(): Collection
    {
        return $this->validations;
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



    public function setHeures(float $heures): self
    {
        $oldHeures = $this->getHeures() ?: 0;
        $newHeures = $heures - $oldHeures;

        $prevu = $this->getEntityManager()
            ->getRepository(TypeVolumeHoraire::class)
            ->findOneBy(['code' => TypeVolumeHoraire::CODE_PREVU]);

        if ($newHeures != 0) {
            $nvh = new VolumeHoraireMission();
            $nvh->setTypeVolumeHoraire($prevu);
            $nvh->setMission($this);
            $this->addVolumeHoraire($nvh);
            $nvh->setHeures($newHeures);
        }

        return $this;
    }



    public function addVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $this->volumesHoraires[] = $volumeHoraireMission;

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



    public function addValidation(Validation $validation): self
    {
        $this->validations[] = $validation;
        foreach ($this->getVolumesHorairesPrevus() as $vh) {
            if (!$vh->isValide()) {
                $vh->addValidation($validation);
            }
        }

        return $this;
    }



    /**
     * @return Collection|VolumeHoraireMission[]
     */
    public function getVolumesHorairesPrevus(): Collection
    {
        return $this->volumesHoraires->filter(function (VolumeHoraireMission $vhm) {
            return $vhm->getTypeVolumeHoraire()->isPrevu();
        });
    }



    public function removeValidation(Validation $validation): self
    {
        $this->validations->removeElement($validation);
        foreach ($this->getVolumesHorairesPrevus() as $vh) {
            $vh->removeValidation($validation);
        }

        return $this;
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



    public function removeVolumeHoraire(VolumeHoraireMission $volumeHoraireMission): self
    {
        $this->volumesHoraires->removeElement($volumeHoraireMission);

        return $this;
    }



    public function hasContrat(): bool
    {
        /** @var VolumeHoraireMission[] $vhs */
        $vhs = $this->getVolumesHorairesPrevus();

        foreach ($vhs as $vh) {
            if ($vh->estNonHistorise() && $vh->getContrat() && $vh->getContrat()->estFinalise()) {
                return true;
            }
        }

        return false;
    }



    public function canSaisie(): bool
    {
        return true;
    }



    public function canAddHeures(): bool
    {
        return true;
    }



    public function canValider(): bool
    {
        return
            !$this->isValide()
            && $this->tauxRemu != null
            && $this->getHeures() > 0;
    }



    public function canDevalider(): bool
    {
        return $this->isValide() && !$this->hasSuivi();
    }



    public function hasSuivi(): bool
    {
        return $this->heuresRealisees() > 0;
    }



    public function heuresRealisees(?bool $validees = null): float
    {
        $vhs = $this->getVolumesHorairesRealises();

        $heures = 0;

        foreach ($vhs as $vh) {
            if ($vh->estNonHistorise()) {
                if ($validees === true) {
                    if ($vh->isValide()) {
                        $heures += $vh->getHeures();
                    }
                } else {
                    $heures += $vh->getHeures();
                }
            }
        }

        return $heures;
    }



    /**
     * @return Collection|VolumeHoraireMission[]
     */
    public function getVolumesHorairesRealises(): Collection
    {
        return $this->volumesHoraires->filter(function (VolumeHoraireMission $vhm) {
            return $vhm->getTypeVolumeHoraire()->isRealise();
        });
    }



    public function canSupprimer(): bool
    {
        return !$this->isValide();
    }



    public function canAddSuivi(\DateTime $date): bool
    {
        $dateOk = $this->getDateDebut() <= $date && $this->getDateFin() >= $date;

        return $this->isValide() && $dateOk;
    }



    public function getHeuresComplFi(): float
    {
        return 0;
    }



    public function getHeuresComplFc(): float
    {
        return 0;
    }



    public function getHeuresPrimes(): float
    {
        return 0;
    }



    public function getHeuresComplFa(): float
    {
        return 0;
    }



    public function getHeuresComplReferentiel(): float
    {
        return 0;
    }



    public function getHeuresCompl(TypeHeures $typeHeures): float
    {
        return $this->heuresRealisees(true);
    }



    public function getHeuresMission(): float
    {
        return $this->heuresRealisees(true);
    }



    public function getDefaultCentreCout(TypeHeures $typeHeures): ?CentreCout
    {
        return null;
    }



    public function getDefaultDomaineFonctionnel(): ?DomaineFonctionnel
    {
        return null;
    }



    public function isDomaineFonctionnelModifiable(): bool
    {
        return true;
    }



    public function isPayable(): bool
    {
        return $this->isValide();
    }



    public function getPrime(): ?Prime
    {
        return $this->prime;
    }



    public function setPrime(?Prime $prime): self
    {
        $this->prime = $prime;

        return $this;
    }



    public function isPrimeActive(): bool
    {
        return $this->primeActive;
    }



    public function setPrimeActive(bool $primeActive): self
    {
        $this->primeActive = $primeActive;

        return $this;
    }

}
