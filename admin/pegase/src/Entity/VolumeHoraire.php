<?php

namespace Entity;

class VolumeHoraire
{
    private ?string $elementId        = null;

    private ?float  $nombreGroupe     = null;

    private ?float  $volumeHoraire    = null;

    private ?string $typeIntervention = null;

    private ?string $sourceCode       = null;

    private ?int    $anneeDebut       = null;

    private ?string $anneeFin         = null;



    public function getElementId(): ?string
    {
        return $this->elementId;
    }



    public function setElementId(?string $elementId): void
    {
        $this->elementId = $elementId;
    }



    public function getNombreGroupe(): ?float
    {
        return $this->nombreGroupe;
    }



    public function setNombreGroupe(?float $nombreGroupe): void
    {
        $this->nombreGroupe = $nombreGroupe;
    }



    public function getVolumeHoraire(): ?float
    {
        return $this->volumeHoraire;
    }



    public function setVolumeHoraire(?float $volumeHoraire): void
    {
        $this->volumeHoraire = $volumeHoraire;
    }



    public function getTypeIntervention(): string
    {
        return $this->typeIntervention;
    }



    public function setTypeIntervention(?string $typeIntervention): void
    {
        $this->typeIntervention = $typeIntervention;
    }



    public function getSourceCode(): ?string
    {
        return $this->sourceCode;
    }



    public function setSourceCode(?string $sourceCode): void
    {
        $this->sourceCode = $sourceCode;
    }



    public function getAnneeDebut(): ?int
    {
        return $this->anneeDebut;
    }



    public function setAnneeDebut(?int $anneeDebut): void
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

}