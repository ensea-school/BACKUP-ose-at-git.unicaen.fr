<?php

namespace Entity;

use DateTime;

class ObjetFormation
{
    private string  $sourceCode;

    private string  $libelle;

    private ?string $anneeDebut         = null;

    private ?string $anneeFin           = null;

    private string  $structureId;

    private ?int    $anneeUniversitaire = null;

    private ?string $dateDebut          = null;

    private ?string $dateFin            = null;

    private ?string $code               = null;

    private ?int    $tauxFoad           = 0;



    public function getSourceCode(): string
    {
        return $this->sourceCode;
    }



    public function setSourceCode(string $sourceCode): void
    {
        $this->sourceCode = $sourceCode;
    }



    public function getLibelle(): string
    {
        return $this->libelle;
    }



    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }



    public function getAnneeDebut(): string
    {
        return $this->anneeDebut;
    }



    public function setAnneeDebut(string $anneeDebut): void
    {
        $this->anneeDebut = $anneeDebut;
    }



    public function getAnneeFin(): string
    {
        return $this->anneeFin;
    }



    public function setAnneeFin(string $anneeFin): void
    {
        $this->anneeFin = $anneeFin;
    }



    public function getStructureId(): string
    {
        return $this->structureId;
    }



    public function setStructureId(string $structureId): void
    {
        $this->structureId = $structureId;
    }



    public function getAnneeUniversitaire(): ?int
    {
        return $this->anneeUniversitaire;
    }



    public function setAnneeUniversitaire(?int $anneeUniversitaire): void
    {
        $this->anneeUniversitaire = $anneeUniversitaire;
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



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): void
    {
        $this->code = $code;
    }



    public function getTauxFoad(): ?int
    {
        return $this->tauxFoad;
    }



    public function setTauxFoad(?bool $tauxFoad): void
    {
        if ($tauxFoad) {
            $this->tauxFoad = 1;
        } else {
            $this->tauxFoad = 0;
        }
    }

}