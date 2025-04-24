<?php

namespace Contrat\Tbl\Process\Model;

class VolumeHoraire
{
    public Contrat $contrat;

    public ?int       $anneeId                = null;
    public ?int       $structureId            = null;
    public ?int       $serviceId              = null;
    public ?int       $serviceReferentielId   = null;
    public ?int       $missionId              = null;
    public ?int       $volumeHoraireId        = null;
    public ?int       $volumeHoraireRefId     = null;
    public ?int       $volumeHoraireMissionId = null;
    public ?int       $tauxRemuId             = null;
    public ?int       $tauxRemuMajoreId       = null;
    public ?\DateTime $dateFinMission         = null;
    public ?\DateTime $dateDebutMission         = null;
    public float      $cm                     = 0.0;
    public float      $td                     = 0.0;
    public float      $tp                     = 0.0;
    public float      $autres                 = 0.0;
    public float      $heures                 = 0.0;
    public float      $hetd                   = 0.0;
    public ?string    $autreLibelle           = null;
    public ?string    $missionLibelle         = null;
    public ?string    $typeMissionLibelle     = null;



    public function setContrat(Contrat $contrat): void
    {
        $this->contrat              = $contrat;
        $contrat->volumesHoraires[] = $this;
    }
}
