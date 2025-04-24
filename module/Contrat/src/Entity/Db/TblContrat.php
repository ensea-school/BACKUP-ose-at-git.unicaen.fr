<?php

namespace Contrat\Entity\Db;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Validation;
use Doctrine\Common\Collections\Collection;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Paiement\Entity\Db\TauxRemu;
use Service\Entity\Db\TypeService;

class TblContrat
{
    private int         $id;
    private Annee       $annee;
    private Intervenant $intervenant;
    private ?Structure  $structure;
    private string      $uuid;
    private bool        $actif;
    private TypeService $typeService;
    private TypeContrat $typeContrat;
    private ?Contrat    $contrat;
    private ?Contrat    $contratParent;
    private ?int        $numeroAvenant;
    private bool        $prolongation;
    private bool        $edite;
    private bool        $signe;
    private bool        $termine;
    private ?\DateTime  $dateCreation;
    private ?\DateTime  $dateDebut;
    private ?\DateTime  $dateFin;
    private TauxRemu    $tauxRemu;
    private float       $tauxRemuValeur;
    private \DateTime   $tauxRemuDate;
    private TauxRemu    $tauxRemuMajore;
    private float       $tauxRemuMajoreValeur;
    private float       $tauxCongesPayes;
    private ?Validation $validation;
    private float       $totalHeures;
    private float       $totalHeuresFormation;
    private float       $totalHetd;
    private float       $totalGlobalHetd;
    private ?string     $autresLibelles;
    private ?string     $missionsLibelles;
    private ?string     $typesMissionLibelles;

    private int $volumeHoraireIndex;

    /** @var Collection|TblContratVolumeHoraire[] */
    private Collection $volumesHoraires;



    /**
     * @return Collection|TblContratVolumeHoraire[]
     */
    public function getVolumesHoraires(): Collection
    {
        $filteredCollection = $this->volumesHoraires->filter(function ($element) {
            return !empty($element->getService())
                || !empty($element->getServiceReferentiel())
                || !empty($element->getMission());
        });

        return $filteredCollection;
    }



    public function getId(): int
    {
        return $this->id;
    }



    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function getUuid(): string
    {
        return $this->uuid;
    }



    public function isActif(): bool
    {
        return $this->actif;
    }



    public function getTypeService(): TypeService
    {
        return $this->typeService;
    }



    public function getTypeContrat(): TypeContrat
    {
        return $this->typeContrat;
    }



    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }



    public function getContratParent(): ?Contrat
    {
        return $this->contratParent;
    }



    public function getNumeroAvenant(): ?int
    {
        return $this->numeroAvenant;
    }



    public function isProlongation(): bool
    {
        return $this->prolongation;
    }



    public function isEdite(): bool
    {
        return $this->edite;
    }



    public function isSigne(): bool
    {
        return $this->signe;
    }



    public function isTermine(): bool
    {
        return $this->termine;
    }



    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }



    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }



    public function getTauxRemu(): TauxRemu
    {
        return $this->tauxRemu;
    }



    public function getTauxRemuValeur(): float
    {
        return $this->tauxRemuValeur;
    }



    public function getTauxRemuDate(): \DateTime
    {
        return $this->tauxRemuDate;
    }



    public function getTauxRemuMajore(): TauxRemu
    {
        return $this->tauxRemuMajore;
    }



    public function getTauxRemuMajoreValeur(): float
    {
        return $this->tauxRemuMajoreValeur;
    }



    public function getTauxCongesPayes(): float
    {
        return $this->tauxCongesPayes;
    }



    public function getValidation(): ?Validation
    {
        return $this->validation;
    }



    public function getTotalHeures(): float
    {
        return $this->totalHeures;
    }



    public function getTotalHeuresFormation(): float
    {
        return $this->totalHeuresFormation;
    }



    public function getTotalHetd(): float
    {
        return $this->totalHetd;
    }



    public function getTotalGlobalHetd(): float
    {
        return $this->totalGlobalHetd;
    }



    public function getAutresLibelles(): ?string
    {
        return $this->autresLibelles;
    }



    public function getMissionsLibelles(): ?string
    {
        return $this->missionsLibelles;
    }



    public function getTypesMissionLibelles(): ?string
    {
        return $this->typesMissionLibelles;
    }



    public function getVolumeHoraireIndex(): int
    {
        return $this->volumeHoraireIndex;
    }

}
