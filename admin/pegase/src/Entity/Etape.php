<?php

namespace Entity;

class Etape
{
    private ?string $sourceCode           = null;

    private ?string $libelle              = null;

    private ?string $anneeDebut           = null;

    private ?string $anneeFin             = null;

    private ?string $structureId          = null;

    private ?string $typeFormationId      = null;

    private ?string $dateDebut            = null;

    private ?string $dateFin              = null;

    private ?string $anneeUniv            = null;

    private ?string $code                 = null;

    private ?string $domaineFonctionnelId = null;

    private ?int    $niveau               = null;



    public function getSourceCode(): ?string
    {
        return $this->sourceCode;
    }



    public function setSourceCode(?string $sourceCode): void
    {
        $this->sourceCode = $sourceCode;
    }



    public function getLibelle(): ?string
    {
        return $this->libelle;
    }



    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }



    public function getAnneeDebut(): ?string
    {
        return $this->anneeDebut;
    }



    public function setAnneeDebut(?string $anneeDebut): void
    {
        $this->anneeDebut = $anneeDebut;
    }



    public function getAnneeFin(): ?string
    {
        return $this->anneeFin;
    }



    public function setAnneeFin(?string $anneeFin): void
    {
        $this->anneeFin = $anneeFin;
    }



    public function getStructureId(): ?string
    {
        return $this->structureId;
    }



    public function setStructureId(?string $structureId): void
    {
        $this->structureId = $structureId;
    }



    public function getTypeFormationId(): ?string
    {
        return $this->typeFormationId;
    }



    public function setTypeFormationId(?string $typeFormationId): void
    {
        $this->typeFormationId = $typeFormationId;
    }



    public function getDateDebut(): ?string
    {
        return $this->dateDebut;
    }



    public function setDateDebut(?string $dateDebut): void
    {
        $this->dateDebut = $dateDebut;
    }



    public function getDateFin(): ?string
    {
        return $this->dateFin;
    }



    public function setDateFin(?string $dateFin): void
    {
        $this->dateFin = $dateFin;
    }



    public function getAnneeUniv(): ?string
    {
        return $this->anneeUniv;
    }



    public function setAnneeUniv(?string $anneeUniv): void
    {
        $this->anneeUniv = $anneeUniv;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): void
    {
        $this->code = $code;
    }



    public function getDomaineFonctionnelId(): ?string
    {
        return $this->domaineFonctionnelId;
    }



    public function setDomaineFonctionnelId(?string $domaineFonctionnelId): void
    {
        $this->domaineFonctionnelId = $domaineFonctionnelId;
    }



    public function getNiveau(): ?int
    {
        return $this->niveau;
    }



    public function setNiveau(?int $niveau): void
    {
        $this->niveau = $niveau;
    }

}