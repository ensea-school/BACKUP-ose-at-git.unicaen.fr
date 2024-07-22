<?php

namespace Entity;

class Structure
{
    private ?string $code              = null;

    private ?string $libellePrincipale = null;

    private ?string $libelleOfficielle = null;

    private ?string $dateDebut         = null;

    private ?string $dateFin           = null;

    private ?string $temoinVisible     = null;



    public function getCode(): ?string
    {
        return $this->code;
    }



    public function setCode(?string $code): void
    {
        $this->code = $code;
    }



    public function getLibellePrincipale(): ?string
    {
        return $this->libellePrincipale;
    }



    public function setLibellePrincipale(?string $libellePrincipale): void
    {
        $this->libellePrincipale = $libellePrincipale;
    }



    public function getLibelleOfficielle(): ?string
    {
        return $this->libelleOfficielle;
    }



    public function setLibelleOfficielle(?string $libelleOfficielle): void
    {
        $this->libelleOfficielle = $libelleOfficielle;
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



    public function getTemoinVisible(): ?string
    {
        return $this->temoinVisible;
    }



    public function setTemoinVisible(?string $temoinVisible): void
    {
        $this->temoinVisible = $temoinVisible;
    }

}