<?php

namespace Entity;

use DateTime;

class ElementPedagogique
{
    private string  $sourceCode;

    private string  $libelle;

    private ?string $anneeDebut  = null;

    private ?string $anneeFin    = null;

    private ?string $structureId = null;

    private ?string $etapeId     = null;

    private ?string $code        = null;

    private ?int    $tauxFoad    = 0;



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



    public function setStructureId(?string $codeStructure): void
    {
        $this->structureId = $codeStructure;
    }



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): void
    {
        $this->code = $code;
    }



    public function getEtapeId(): ?string
    {
        return $this->etapeId;
    }



    public function setEtapeId(?string $etapeId): void
    {
        $this->etapeId = $etapeId;
    }



    public function getTauxFoad(): ?int
    {
        return $this->tauxFoad;
    }



    public function setTauxFoad(?int $tauxFoad): void
    {
        $this->tauxFoad = $tauxFoad;
    }

}