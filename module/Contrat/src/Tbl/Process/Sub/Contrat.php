<?php

namespace Contrat\Tbl\Process\Sub;


class Contrat
{
    public ?int $id = null;

    public ?string $uuid = null;

    public ?int $intervenantId = null;

    public ?int $structureId = null;

    public ?Contrat $parent = null;

    public int $numeroAvenant = 0;

    public ?\DateTime $debutValidite = null;

    public ?\DateTime $finValidite = null;

    public bool $valide = false; // ou edite ?

    public bool $envoye = false;

    public bool $signe = false;

    public bool $retourne = false;

    //public float $hetd = 0.0;

    public float $totalHetd = 0.0;

    public ?int $processSignatureId = null;

    public ?int $tauxRemuId = null;

    public ?\DateTime $tauxRemuDateEffet = null;

    public float $tauxRemuValeur = 0.0;

    public ?int $tauxRemuMajoreId = null;

    public ?\DateTime $tauxRemuMajoreDateEffet = null;

    public float $tauxRemuMajoreValeur = 0.0;


    /** @var VolumeHoraire[] */
    public array $volumesHoraires = [];

}