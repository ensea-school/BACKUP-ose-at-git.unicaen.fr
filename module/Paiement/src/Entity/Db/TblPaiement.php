<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\Annee;
use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Periode;
use Application\Entity\Db\Structure;
use Mission\Entity\Db\Mission;


class TblPaiement
{
    private int $id;

    private Annee $annee;

    private Intervenant $intervenant;

    private Structure $structure;

    private ?FormuleResultatService $formuleResultatService = null;

    private ?FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null;

    private ?Mission $mission = null;

    private ?Periode $periodePaiement = null;

    private ?MiseEnPaiement $miseEnPaiement = null;

    private float $heuresAPayer = 0;

    private float $heuresAPayerPond = 0;

    private float $heuresDemandees = 0;

    private float $heuresPayees = 0;



    public function getId(): int
    {
        return $this->id;
    }



    public function getFormuleResultatService(): ?FormuleResultatService
    {
        return $this->formuleResultatService;
    }



    public function getFormuleResultatServiceReferentiel(): ?FormuleResultatServiceReferentiel
    {
        return $this->formuleResultatServiceReferentiel;
    }



    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    public function getServiceAPayer(): ServiceAPayerInterface
    {
        if ($this->formuleResultatService) return $this->formuleResultatService;
        if ($this->formuleResultatServiceReferentiel) return $this->formuleResultatServiceReferentiel;
        if ($this->mission) return $this->mission;

        return null;
    }



    public function getStructure(): Structure
    {
        return $this->structure;
    }



    public function getPeriodePaiement(): ?Periode
    {
        return $this->periodePaiement;
    }



    public function getMiseEnPaiement(): ?MiseEnPaiement
    {
        return $this->miseEnPaiement;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    public function getHeuresAPayer(): float
    {
        return $this->heuresAPayer;
    }



    public function getHeuresAPayerPond(): float
    {
        return $this->heuresAPayerPond;
    }



    public function getHeuresDemandees(): float
    {
        return $this->heuresDemandees;
    }



    public function getHeuresPayees(): float
    {
        return $this->heuresPayees;
    }

}