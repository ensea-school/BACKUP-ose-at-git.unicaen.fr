<?php

namespace Contrat\Entity\Db;

use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Mission\Entity\Db\Mission;
use Mission\Entity\Db\VolumeHoraireMission;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;

class TblContratVolumeHoraire
{
    private int                       $id;
    private string                    $uuid;
    private int                       $volumeHoraireIndex;
    private ?Service                  $service;
    private ?ServiceReferentiel       $serviceReferentiel;
    private ?Mission                  $mission;
    private ?VolumeHoraire            $volumeHoraire;
    private ?VolumeHoraireMission     $volumeHoraireMission;
    private ?VolumeHoraireReferentiel $volumeHoraireRef;
    private float                     $heures;
    private float                     $cm;
    private float                     $td;
    private float                     $tp;
    private float                     $autres;
    private float                     $hetd;
    private ?string                   $autreLibelle;

    private TblContrat $tblContrat;



    public function getId(): int
    {
        return $this->id;
    }



    public function getUuid(): string
    {
        return $this->uuid;
    }



    public function getVolumeHoraireIndex(): int
    {
        return $this->volumeHoraireIndex;
    }



    public function getService(): ?Service
    {
        return $this->service;
    }



    public function getServiceReferentiel(): ?ServiceReferentiel
    {
        return $this->serviceReferentiel;
    }



    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    public function getVolumeHoraire(): ?VolumeHoraire
    {
        return $this->volumeHoraire;
    }



    public function getVolumeHoraireMission(): ?VolumeHoraireMission
    {
        return $this->volumeHoraireMission;
    }



    public function getVolumeHoraireRef(): ?VolumeHoraireReferentiel
    {
        return $this->volumeHoraireRef;
    }



    public function getHeures(): float
    {
        return $this->heures;
    }



    public function getCm(): float
    {
        return $this->cm;
    }



    public function getTd(): float
    {
        return $this->td;
    }



    public function getTp(): float
    {
        return $this->tp;
    }



    public function getAutres(): float
    {
        return $this->autres;
    }



    public function getHetd(): float
    {
        return $this->hetd;
    }



    public function getAutreLibelle(): ?string
    {
        return $this->autreLibelle;
    }



    public function getTblContrat(): TblContrat
    {
        return $this->tblContrat;
    }


}
