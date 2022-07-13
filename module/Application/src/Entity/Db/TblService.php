<?php

namespace Application\Entity\Db;

use Service\Entity\Db\TypeVolumeHoraire;

class TblService
{
    private int               $id;

    private Annee             $annee;

    private Intervenant       $intervenant;

    private bool              $actif                    = false;

    private Service           $service;

    private ?Structure        $structure;

    private TypeVolumeHoraire $typeVolumeHoraire;

    private bool              $hasHeuresMauvaisePeriode = false;

    private int               $nbvh                     = 0;

    private float             $heures                   = 0;

    private int               $valide                   = 0;



    public function getId(): int
    {
        return $this->id;
    }



    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getActif(): bool
    {
        return $this->actif;
    }



    public function getService(): Service
    {
        return $this->service;
    }



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function getTypeVolumeHoraire(): TypeVolumeHoraire
    {
        return $this->typeVolumeHoraire;
    }



    public function getHasHeuresMauvaisePeriode(): bool
    {
        return $this->hasHeuresMauvaisePeriode;
    }



    public function getNbvh(): int
    {
        return $this->nbvh;
    }



    public function getHeures(): float|int
    {
        return $this->heures;
    }



    public function getValide(): int
    {
        return $this->valide;
    }

}

