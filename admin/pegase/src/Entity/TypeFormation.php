<?php

namespace Entity;

class TypeFormation implements OdfEntityInterface
{

    private Odf     $odf;

    private string  $libelleLong;

    private string  $libelleCourt;

    private string  $sourceId;

    private string  $sourceCode;

    private ?string $dateDebut  = null;

    private ?string $dateFin    = null;

    private ?string $anneeDebut = null;

    private ?string $anneeFin   = null;



    /**
     * @param Odf $odf
     */
    public function __construct(Odf $odf)
    {
        $this->odf = $odf;
    }



    public function getLibelleLong(): string
    {
        return $this->libelleLong;
    }



    public function setLibelleLong(string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
    }



    public function getLibelleCourt(): string
    {
        return $this->libelleCourt;
    }



    public function setLibelleCourt(string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }



    public function getSourceId(): string
    {
        return $this->sourceId;
    }



    public function setSourceId(string $sourceId): void
    {
        $this->sourceId = $sourceId;
    }



    public function getSourceCode(): string
    {
        return $this->sourceCode;
    }



    public function setSourceCode(string $sourceCode): void
    {
        $this->sourceCode = $sourceCode;
    }



    public function getOdf(): Odf
    {
        return $this->odf;
    }



    public function setOdf(Odf $odf): void
    {
        $this->odf = $odf;
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

}