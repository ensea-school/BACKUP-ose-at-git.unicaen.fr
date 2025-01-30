<?php

namespace Contrat\Entity\Db;

use Application\Entity\Db\Annee;
use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Intervenant\Entity\Db\Intervenant;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Paiement\Entity\Db\TauxRemu;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;
use Service\Entity\Db\TypeService;

class TblContrat
{
    private int                       $id;
    private int                       $actif                = 1;
    private ?string                   $uuid                 = null;
    private Annee                     $annee;
    private ?Intervenant              $intervenant          = null;
    private ?Structure                $structure            = null;
    private ?Contrat                  $contrat              = null;
    private ?Contrat                  $contratParent        = null;
    private ?TypeContrat              $typeContrat          = null;
    private float                     $edite;
    private float                     $signe;
    private ?\DateTime                $dateDebut            = null;
    private ?\DateTime                $dateFin              = null;
    private ?\DateTime                $dateCreation         = null;
    private ?Mission                  $mission              = null;
    private ?Service                  $service              = null;
    private ?ServiceReferentiel       $serviceReferentiel   = null;
    private ?TypeService              $typeService          = null;
    private ?float                    $cm                   = null;
    private ?float                    $td                   = null;
    private ?float                    $tp                   = null;
    private ?float                    $autres               = null;
    private ?string                   $autreLibelle         = null;
    private ?float                    $heures               = null;
    private ?float                    $hetd                 = null;
    private ?TauxRemu                 $tauxRemu             = null;
    private ?float                    $tauxRemuValeur       = null;
    private ?\DateTime                $tauxRemuDate         = null;
    private ?TauxRemu                 $tauxRemuMajore       = null;
    private ?float                    $tauxRemuMajoreValeur = null;
    private ?\DateTime                $tauxRemuMajoreDate   = null;
    private ?float                    $tauxCongesPayes      = null;
    private ?VolumeHoraire            $volumeHoraire        = null;
    private ?VolumeHoraireMission     $volumeHoraireMission = null;
    private ?VolumeHoraireReferentiel $volumeHoraireRef     = null;



    // Getters and Setters
    public function getId(): int
    {
        return $this->id;
    }



    public function setId(int $id): void
    {
        $this->id = $id;
    }



    public function getActif(): int
    {
        return $this->actif;
    }



    public function setActif(int $actif): void
    {
        $this->actif = $actif;
    }



    public function getUuid(): ?string
    {
        return $this->uuid;
    }



    public function setUuid(?string $uuid): void
    {
        $this->uuid = $uuid;
    }



    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    public function setAnnee(Annee $annee): void
    {
        $this->annee = $annee;
    }



    public function getIntervenant(): ?Intervenant
    {
        return $this->intervenant;
    }



    public function setIntervenant(?Intervenant $intervenant): void
    {
        $this->intervenant = $intervenant;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function setStructure(?Structure $structure): void
    {
        $this->structure = $structure;
    }



    public function getContrat(): ?Contrat
    {
        return $this->contrat;
    }



    public function setContrat(?Contrat $contrat): void
    {
        $this->contrat = $contrat;
    }



    public function getContratParent(): ?Contrat
    {
        return $this->contratParent;
    }



    public function setContratParent(?Contrat $contratParent): void
    {
        $this->contratParent = $contratParent;
    }



    public function getTypeContrat(): ?TypeContrat
    {
        return $this->typeContrat;
    }



    public function setTypeContrat(?TypeContrat $typeContrat): void
    {
        $this->typeContrat = $typeContrat;
    }



    public function getEdite(): float
    {
        return $this->edite;
    }



    public function setEdite(float $edite): void
    {
        $this->edite = $edite;
    }



    public function getSigne(): float
    {
        return $this->signe;
    }



    public function setSigne(float $signe): void
    {
        $this->signe = $signe;
    }



    public function getDateDebut(): ?\DateTime
    {
        return $this->dateDebut;
    }



    public function setDateDebut(?\DateTime $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }



    public function getDateFin(): ?\DateTime
    {
        return $this->dateFin;
    }



    public function setDateFin(?\DateTime $dateFin): void
    {
        $this->dateFin = $dateFin;
    }



    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }



    public function setDateCreation(?\DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }



    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    public function setMission(?Mission $mission): void
    {
        $this->mission = $mission;
    }



    public function getService(): ?Service
    {
        return $this->service;
    }



    public function setService(?Service $service): void
    {
        $this->service = $service;
    }



    public function getServiceReferentiel(): ?ServiceReferentiel
    {
        return $this->serviceReferentiel;
    }



    public function setServiceReferentiel(?ServiceReferentiel $serviceReferentiel): void
    {
        $this->serviceReferentiel = $serviceReferentiel;
    }



    public function getTypeService(): ?TypeService
    {
        return $this->typeService;
    }



    public function setTypeService(?int $typeService): void
    {
        $this->typeService = $typeService;
    }



    public function getCm(): ?float
    {
        return $this->cm;
    }



    public function setCm(?float $cm): void
    {
        $this->cm = $cm;
    }



    public function getTd(): ?float
    {
        return $this->td;
    }



    public function setTd(?float $td): void
    {
        $this->td = $td;
    }



    public function getTp(): ?float
    {
        return $this->tp;
    }



    public function setTp(?float $tp): void
    {
        $this->tp = $tp;
    }



    public function getAutres(): ?float
    {
        return $this->autres;
    }



    public function setAutres(?float $autres): void
    {
        $this->autres = $autres;
    }



    public function getAutreLibelle(): ?string
    {
        return $this->autreLibelle;
    }



    public function setAutreLibelle(?string $autreLibelle): void
    {
        $this->autreLibelle = $autreLibelle;
    }



    public function getHeures(): ?float
    {
        return $this->heures;
    }



    public function setHeures(?float $heures): void
    {
        $this->heures = $heures;
    }



    public function getHetd(): ?float
    {
        return $this->hetd;
    }



    public function setHetd(?float $hetd): void
    {
        $this->hetd = $hetd;
    }



    public function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    public function setTauxRemu(?TauxRemu $tauxRemu): void
    {
        $this->tauxRemu = $tauxRemu;
    }



    public function getTauxRemuValeur(): ?float
    {
        return $this->tauxRemuValeur;
    }



    public function setTauxRemuValeur(?float $tauxRemuValeur): void
    {
        $this->tauxRemuValeur = $tauxRemuValeur;
    }



    public function getTauxRemuDate(): ?\DateTime
    {
        return $this->tauxRemuDate;
    }



    public function setTauxRemuDate(?\DateTime $tauxRemuDate): void
    {
        $this->tauxRemuDate = $tauxRemuDate;
    }



    public function getTauxRemuMajore(): ?TauxRemu
    {
        return $this->tauxRemuMajore;
    }



    public function setTauxRemuMajore(?TauxRemu $tauxRemuMajore): void
    {
        $this->tauxRemuMajore = $tauxRemuMajore;
    }



    public function getTauxRemuMajoreValeur(): ?float
    {
        return $this->tauxRemuMajoreValeur;
    }



    public function setTauxRemuMajoreValeur(?float $tauxRemuMajoreValeur): void
    {
        $this->tauxRemuMajoreValeur = $tauxRemuMajoreValeur;
    }



    public function getTauxRemuMajoreDate(): ?\DateTime
    {
        return $this->tauxRemuMajoreDate;
    }



    public function setTauxRemuMajoreDate(?\DateTime $tauxRemuMajoreDate): void
    {
        $this->tauxRemuMajoreDate = $tauxRemuMajoreDate;
    }



    public function getTauxCongesPayes(): ?float
    {
        return $this->tauxCongesPayes;
    }



    public function setTauxCongesPayes(?float $tauxCongesPayes): void
    {
        $this->tauxCongesPayes = $tauxCongesPayes;
    }



    public function getVolumeHoraire(): ?VolumeHoraire
    {
        return $this->volumeHoraire;
    }



    public function setVolumeHoraire(?VolumeHoraireMission $volumeHoraire): void
    {
        $this->volumeHoraire = $volumeHoraire;
    }



    public function getVolumeHoraireMission(): ?VolumeHoraireMission
    {
        return $this->volumeHoraireMission;
    }



    public function setVolumeHoraireMission(?VolumeHoraireReferentiel $volumeHoraireMission): void
    {
        $this->volumeHoraireMission = $volumeHoraireMission;
    }



    public function getVolumeHoraireRef(): ?VolumeHoraireReferentiel
    {
        return $this->volumeHoraireRef;
    }



    public function setVolumeHoraireRef(?int $volumeHoraireRef): void
    {
        $this->volumeHoraireRef = $volumeHoraireRef;
    }





}
