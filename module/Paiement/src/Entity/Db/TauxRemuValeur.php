<?php

namespace Paiement\Entity\Db;

class TauxRemuValeur
{
    protected ?int       $id        = null;

    protected ?\DateTime $dateEffet = null;

    protected ?TauxRemu  $tauxRemu  = null;

    protected float      $valeur    = 0;



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getDateEffet(): ?\DateTime
    {
        return $this->dateEffet;
    }



    public function setDateEffet(?\DateTime $dateEffet): TauxRemuValeur
    {
        $this->dateEffet = $dateEffet;

        return $this;
    }



    public function getTauxRemu(): ?TauxRemu
    {
        return $this->tauxRemu;
    }



    public function setTauxRemu(?TauxRemu $tauxRemu): TauxRemuValeur
    {
        $this->tauxRemu = $tauxRemu;

        return $this;
    }



    public function getValeur(): float
    {
        return $this->valeur;
    }



    public function setValeur(float $valeur): TauxRemuValeur
    {
        $this->valeur = $valeur;

        return $this;
    }
}
