<?php

namespace Entity;

use DateTime;

class CheminPedagogique
{
    private ?string $id;

    private ?string $etapeCode;

    private ?string $etapeId;

    private ?string $elementPedagogiqueCode;

    private ?string $elementPedagogiqueId;

    private ?string $sourceCode;

    private ?string $anneeDebut = null;

    private ?string $anneeFin   = null;



    public function getId(): ?string
    {
        return $this->id;
    }



    public function setId(?string $id): void
    {
        $this->id = $id;
    }



    public function getEtapeCode(): ?string
    {
        return $this->etapeCode;
    }



    public function setEtapeCode(?string $etapeCode): void
    {
        $this->etapeCode = $etapeCode;
    }



    public function getElementPedagogiqueCode(): ?string
    {
        return $this->elementPedagogiqueCode;
    }



    public function setElementPedagogiqueCode(?string $elementPedagogiqueCode): void
    {
        $this->elementPedagogiqueCode = $elementPedagogiqueCode;
    }



    public function getSourceCode(): ?string
    {
        return $this->sourceCode;
    }



    public function setSourceCode(?string $sourceCode): void
    {
        $this->sourceCode = $sourceCode;
    }



    public function getEtapeId(): ?string
    {
        return $this->etapeId;
    }



    public function setEtapeId(?string $etapeId): void
    {
        $this->etapeId = $etapeId;
    }



    public function getElementPedagogiqueId(): ?string
    {
        return $this->elementPedagogiqueId;
    }



    public function setElementPedagogiqueId(?string $elementPedagogiqueId): void
    {
        $this->elementPedagogiqueId = $elementPedagogiqueId;
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