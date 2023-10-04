<?php

namespace Application\Entity\Db;

use Lieu\Entity\Db\Structure;

class TblContrat
{
    private int         $id;

    private Annee       $annee;

    private Intervenant $intervenant;

    private ?Structure  $structure;

    private bool        $actif = false;

    private float       $edite = 0;

    private float       $signe = 0;

    private float       $nbvh  = 0;



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



    public function getStructure(): ?Structure
    {
        return $this->structure;
    }



    public function getActif(): bool
    {
        return $this->actif;
    }



    public function getEdite(): float|int
    {
        return $this->edite;
    }



    public function getSigne(): float|int
    {
        return $this->signe;
    }



    public function getNbvh(): float|int
    {
        return $this->nbvh;
    }

}