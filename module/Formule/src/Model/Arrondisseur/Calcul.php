<?php

namespace Formule\Model\Arrondisseur;

class Calcul
{
    protected Valeur $total;

    /** @var array|Valeur[] */
    protected array $valeurs = [];



    public function __construct(Valeur $total)
    {
        $this->total = $total;
    }



    public function getTotal(): Valeur
    {
        return $this->total;
    }



    public function setTotal(Valeur $total): Calcul
    {
        $this->total = $total;
        return $this;
    }



    public function getValeurs(): array
    {
        return $this->valeurs;
    }



    public function setValeurs(array $valeurs): Calcul
    {
        $this->valeurs = $valeurs;
        return $this;
    }



    public function addValeur(Valeur $valeur): void
    {
        $this->valeurs[] = $valeur;
    }

}