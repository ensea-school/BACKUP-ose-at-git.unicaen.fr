<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\Annee;
use Application\Entity\Db\Periode;
use Enseignement\Entity\Db\Service;
use Intervenant\Entity\Db\Intervenant;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use OffreFormation\Entity\Db\TypeHeures;
use Referentiel\Entity\Db\ServiceReferentiel;


class TblPaiement
{
    private int $id;

    private Annee $annee;

    private TypeIntervenant $typeIntervenant;

    private Intervenant $intervenant;

    private Structure $structure;

    private ?Service $service = null;

    private ?ServiceReferentiel $serviceReferentiel = null;

    private ?Mission $mission = null;

    private ?TypeHeures $typeHeures;

    private ?Periode $periodePaiement = null;

    private ?MiseEnPaiement $miseEnPaiement = null;

    private ?CentreCout $centreCout = null;

    private ?DomaineFonctionnel $domaineFonctionnel = null;

    private float $heuresAPayerAA = 0.0;

    private float $heuresAPayerAC = 0.0;

    private float $heuresDemandeesAA = 0.0;

    private float $heuresDemandeesAC = 0.0;

    private float $heuresPayeesAA = 0.0;

    private float $heuresPayeesAC = 0.0;

    private ?Periode                           $periodeEnseignement               = null;



    public function getId(): int
    {
        return $this->id;
    }



    public function getServiceReferentiel(): ?ServiceReferentiel
    {
        return $this->serviceReferentiel;
    }



    public function getService(): ?Service
    {
        return $this->service;
    }





    public function getMission(): ?Mission
    {
        return $this->mission;
    }



    public function getStructure(): Structure
    {
        return $this->structure;
    }



    public function getTypeHeures(): ?TypeHeures
    {
        return $this->typeHeures;
    }



    public function getPeriodePaiement(): ?Periode
    {
        return $this->periodePaiement;
    }



    public function getMiseEnPaiement(): ?MiseEnPaiement
    {
        return $this->miseEnPaiement;
    }



    public function getCentreCout(): ?CentreCout
    {
        return $this->centreCout;
    }



    public function getDomaineFonctionnel(): ?DomaineFonctionnel
    {
        return $this->domaineFonctionnel;
    }



    public function getTypeIntervenant(): TypeIntervenant
    {
        return $this->typeIntervenant;
    }



    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getAnnee(): Annee
    {
        return $this->annee;
    }



    public function getHeuresAPayerAA(): float
    {
        return $this->heuresAPayerAA;
    }



    public function getHeuresAPayerAC(): float
    {
        return $this->heuresAPayerAC;
    }



    public function getHeuresAPayer(): float
    {
        return round($this->heuresAPayerAA + $this->getHeuresAPayerAC(), 2);
    }



    public function getHeuresDemandeesAA(): float
    {
        return $this->heuresDemandeesAA;
    }



    public function getHeuresDemandeesAC(): float
    {
        return $this->heuresDemandeesAC;
    }



    public function getHeuresDemandees(): float
    {
        return round($this->heuresDemandeesAA + $this->heuresDemandeesAC, 2);
    }



    public function getHeuresPayeesAA(): float
    {
        return $this->heuresPayeesAA;
    }



    public function getHeuresPayeesAC(): float
    {
        return $this->heuresPayeesAC;
    }



    public function getHeuresPayees(): float
    {
        return round($this->heuresPayeesAA + $this->heuresPayeesAC, 2);
    }



    public function getPeriodeEnseignement (): ?Periode
    {
        return $this->periodeEnseignement;
    }
}