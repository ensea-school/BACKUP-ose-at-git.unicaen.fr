<?php

namespace Contrat\Tbl\Process\Sub;

class VolumeHoraire
{
    public ?int $anneeId                = null;
    public ?int $structureId            = null;
    public ?int $serviceId              = null;
    public ?int $serviceReferentielId   = null;
    public ?int $missionId              = null;
    public ?int $volumeHoraireId        = null;
    public ?int $volumeHoraireRefId     = null;
    public ?int $volumeHoraireMissionId = null;
    public ?int $tauxRemuId             = null;
    public ?int $tauxRemuMajoreId       = null;

    public float   $cm           = 0.0;
    public float   $td           = 0.0;
    public float   $tp           = 0.0;
    public float   $autres       = 0.0;
    public float   $heures       = 0.0;
    public float   $hetd         = 0.0;
    public ?string $autreLibelle = null;
}