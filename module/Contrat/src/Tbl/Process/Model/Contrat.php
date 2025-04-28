<?php

namespace Contrat\Tbl\Process\Model;


use Application\Entity\Db\Annee;
use Service\Entity\Db\TypeService;

class Contrat
{
    public bool         $actif         = false;
    public bool         $historise     = false;
    public ?int         $id            = null;
    public ?TypeService $typeService   = null;
    public ?Annee       $annee         = null;
    public bool         $isMission     = false;
    public ?string      $uuid          = null;
    public ?int         $intervenantId = null;
    public ?int         $structureId   = null;
    public ?int         $validationId  = null;
    public ?Contrat     $parent        = null;
    public bool         $prolongation  = false;

    /** @var Contrat[] */
    public array      $avenants             = [];
    public int        $numeroAvenant        = 0;
    public ?\DateTime $debutValidite        = null;
    public ?\DateTime $finValidite          = null;
    public ?\DateTime $histoCreation        = null;
    public bool       $edite                = false;
    public bool       $envoye               = false;
    public bool       $retourne             = false;
    public bool       $signe                = false;
    public bool       $termine              = false;
    public float      $totalHetd            = 0.0;
    public float      $totalGlobalHetd      = 0.0;
    public ?int       $tauxRemuId           = null;
    public ?\DateTime $tauxRemuDate         = null;
    public float      $tauxRemuValeur       = 0.0;
    public ?int       $tauxRemuMajoreId     = null;
    public float      $tauxRemuMajoreValeur = 0.0;
    public float      $tauxCongesPayes      = 0.0;
    public ?string    $autresLibelles       = null;
    public ?string    $missionsLibelles     = null;
    public ?string    $typesMissionLibelles = null;

    /** @var VolumeHoraire[] */
    public array $volumesHoraires = [];
    public float $totalHeures     = 0.0;



    public function __construct(?string $uuid = null)
    {
        if ($uuid) {
            $this->uuid = $uuid;
        }
    }



    public function hasStructureId(?int $structureId): bool
    {
        if ($this->structureId) {
            return $this->structureId == $structureId;
        }

        if (empty($this->volumesHoraires) && $this->parent) {
            // On est dans un contexte de missions, donc pas de contrat sans VHs, donc on a un parent!
            return $this->parent->hasStructureId($structureId);
        }

        foreach ($this->volumesHoraires as $vh) {
            if ($vh->structureId === $structureId) {
                return true;
            }
        }

        return false;
    }



    public function hasMissionId(int $missionId): bool
    {
        if (!$this->isMission) {
            return false;
        }

        if (empty($this->volumesHoraires) && $this->parent) {
            // On est dans un contexte de missions, donc pas de contrat sans VHs, donc on a un parent!
            return $this->parent->hasMissionId($missionId);
        }

        foreach ($this->volumesHoraires as $vh) {
            if ($vh->missionId === $missionId) {
                return true;
            }
        }

        return false;
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



    public function setParent(Contrat $parent): void
    {
        if ($this->parent !== $parent) {
            $this->parent       = $parent;
            $parent->avenants[] = $this;
        }
    }
}
