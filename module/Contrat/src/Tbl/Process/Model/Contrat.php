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

    public array $avenants = [];

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



    public function getMissionId(): ?int
    {
        if (empty($this->volumesHoraires) && $this->parent) {
            // On est dans un contexte de missions, donc pas de contrat sans VHs, donc on a un parent!
            return $this->parent->getMissionId();
        }

        // On retourne la mission ID s'il y en a une et une seule,
        // et NULL s'il n'y en a aucune ou plusieurs <>
        $missionId = null;
        foreach ($this->volumesHoraires as $vh) {
            if ($vh->missionId) {
                if (!$missionId) {
                    $missionId = $vh->missionId;
                } else {
                    if ($vh->missionId != $missionId) {
                        return null;
                    }
                }
            }
        }
        return $missionId;
    }
}