<?php

namespace Contrat\Tbl\Process\Model;


class Contrat
{
    public bool $actif = false;

    public \DateTime $anneeDateDebut;

    public ?int $id = null;

    public bool $isMission = false;

    public ?string $uuid = null;

    public ?int $intervenantId = null;

    public ?int $structureId = null;

    public ?Contrat $parent = null;

//    public array $avenants = [];

    public int $numeroAvenant = 0;

    public ?\DateTime $debutValidite = null;

    public ?\DateTime $finValidite = null;

    public ?\DateTime $histoCreation = null;

    public bool $edite = false;

    public bool $envoye = false;

    public bool $retourne = false;

    public bool $signe = false;

    //public float $hetd = 0.0;

    public float $totalHetd = 0.0;

    public ?int $tauxRemuId = null;

    public ?\DateTime $tauxRemuDate = null;

    public float $tauxRemuValeur = 0.0;

    public ?int $tauxRemuMajoreId = null;

    public float $tauxRemuMajoreValeur = 0.0;


    /** @var VolumeHoraire[] */
    public array $volumesHoraires = [];



    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

}