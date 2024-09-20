<?php

namespace Contrat\Entity\Db;

class TblContrat
{
    private int $id;
    private int $actif;
    private ?string $uuid = null;
    private int $annee;
    private int $intervenant;
    private ?int $structure = null;
    private ?float $contrat = null;
    private ?float $contratParent = null;
    private ?int $typeContrat = null;
    private float $edite;
    private float $signe;
    private ?\DateTime $dateDebut = null;
    private ?\DateTime $dateFin = null;
    private ?\DateTime $dateCreation = null;
    private ?int $mission = null;
    private ?int $service = null;
    private ?int $serviceReferentiel = null;
    private ?int $typeService = null;
    private ?float $cm = null;
    private ?float $td = null;
    private ?float $tp = null;
    private ?float $autres = null;
    private ?string $autreLibelle = null;
    private ?float $heures = null;
    private ?float $hetd = null;
    private ?int $tauxRemu = null;
    private ?float $tauxRemuValeur = null;
    private ?\DateTime $tauxRemuDate = null;
    private ?int $tauxRemuMajore = null;
    private ?float $tauxRemuMajoreValeur = null;
    private ?\DateTime $tauxRemuMajoreDate = null;
    private ?float $tauxCongesPayes = null;
    private ?int $volumeHoraire = null;
    private ?int $volumeHoraireMission = null;
    private ?int $volumeHoraireRef = null;
    private float $nbvh;

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



    public function getAnnee(): int
    {
        return $this->annee;
    }



    public function setAnnee(int $annee): void
    {
        $this->annee = $annee;
    }



    public function getIntervenant(): int
    {
        return $this->intervenant;
    }



    public function setIntervenant(int $intervenant): void
    {
        $this->intervenant = $intervenant;
    }



    public function getStructure(): ?int
    {
        return $this->structure;
    }



    public function setStructure(?int $structure): void
    {
        $this->structure = $structure;
    }



    public function getContrat(): ?float
    {
        return $this->contrat;
    }



    public function setContrat(?float $contrat): void
    {
        $this->contrat = $contrat;
    }



    public function getContratParent(): ?float
    {
        return $this->contratParent;
    }



    public function setContratParent(?float $contratParent): void
    {
        $this->contratParent = $contratParent;
    }



    public function getTypeContrat(): ?int
    {
        return $this->typeContrat;
    }



    public function setTypeContrat(?int $typeContrat): void
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



    public function getMission(): ?int
    {
        return $this->mission;
    }



    public function setMission(?int $mission): void
    {
        $this->mission = $mission;
    }



    public function getService(): ?int
    {
        return $this->service;
    }



    public function setService(?int $service): void
    {
        $this->service = $service;
    }



    public function getServiceReferentiel(): ?int
    {
        return $this->serviceReferentiel;
    }



    public function setServiceReferentiel(?int $serviceReferentiel): void
    {
        $this->serviceReferentiel = $serviceReferentiel;
    }



    public function getTypeService(): ?int
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



    public function getTauxRemu(): ?int
    {
        return $this->tauxRemu;
    }



    public function setTauxRemu(?int $tauxRemu): void
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



    public function getTauxRemuMajore(): ?int
    {
        return $this->tauxRemuMajore;
    }



    public function setTauxRemuMajore(?int $tauxRemuMajore): void
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



    public function getVolumeHoraire(): ?int
    {
        return $this->volumeHoraire;
    }



    public function setVolumeHoraire(?int $volumeHoraire): void
    {
        $this->volumeHoraire = $volumeHoraire;
    }



    public function getVolumeHoraireMission(): ?int
    {
        return $this->volumeHoraireMission;
    }



    public function setVolumeHoraireMission(?int $volumeHoraireMission): void
    {
        $this->volumeHoraireMission = $volumeHoraireMission;
    }



    public function getVolumeHoraireRef(): ?int
    {
        return $this->volumeHoraireRef;
    }



    public function setVolumeHoraireRef(?int $volumeHoraireRef): void
    {
        $this->volumeHoraireRef = $volumeHoraireRef;
    }



    public function getNbvh(): float
    {
        return $this->nbvh;
    }



    public function setNbvh(float $nbvh): void
    {
        $this->nbvh = $nbvh;
    }


}
