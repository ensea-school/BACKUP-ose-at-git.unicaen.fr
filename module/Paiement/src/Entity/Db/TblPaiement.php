<?php

namespace Paiement\Entity\Db;

use Application\Entity\Db\Annee;
use Application\Entity\Db\DomaineFonctionnel;
use Application\Entity\Db\FormuleResultatService;
use Application\Entity\Db\FormuleResultatServiceReferentiel;
use Application\Entity\Db\Intervenant;
use Application\Entity\Db\Periode;
use Intervenant\Entity\Db\TypeIntervenant;
use Lieu\Entity\Db\Structure;
use Mission\Entity\Db\Mission;
use OffreFormation\Entity\Db\TypeHeures;


class TblPaiement
{
    private int                                $id;

    private Annee                              $annee;

    private TypeIntervenant                    $typeIntervenant;

    private Intervenant                        $intervenant;

    private Structure                          $structure;

    private ?FormuleResultatService            $formuleResultatService            = null;

    private ?FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null;

    private ?Mission                           $mission                           = null;

    private ?TypeHeures                        $typeHeures;

    private ?Periode                           $periodePaiement                   = null;

    private ?MiseEnPaiement                    $miseEnPaiement                    = null;

    private ?CentreCout                        $centreCout                        = null;

    private ?DomaineFonctionnel                $domaineFonctionnel                = null;

    private float                              $heuresAPayerAA                    = 0.0;

    private float                              $heuresAPayerAC                    = 0.0;

    private float                              $heuresDemandeesAA                 = 0.0;

    private float                              $heuresDemandeesAC                 = 0.0;

    private float                              $heuresPayeesAA                    = 0.0;

    private float                              $heuresPayeesAC                    = 0.0;

    private ?Periode                           $periodeEnseignement               = null;



    public function getId (): int
    {
        return $this->id;
    }



    public function getFormuleResultatService (): ?FormuleResultatService
    {
        return $this->formuleResultatService;
    }



    public function getFormuleResultatServiceReferentiel (): ?FormuleResultatServiceReferentiel
    {
        return $this->formuleResultatServiceReferentiel;
    }



    public function getMission (): ?Mission
    {
        return $this->mission;
    }



    public function getServiceAPayer (): ?ServiceAPayerInterface
    {
        if ($this->formuleResultatService) return $this->formuleResultatService;
        if ($this->formuleResultatServiceReferentiel) return $this->formuleResultatServiceReferentiel;
        if ($this->mission) return $this->mission;

        return null;
    }



    public function getStructure (): Structure
    {
        return $this->structure;
    }



    public function getTypeHeures (): ?TypeHeures
    {
        return $this->typeHeures;
    }



    public function getPeriodePaiement (): ?Periode
    {
        return $this->periodePaiement;
    }



    public function getMiseEnPaiement (): ?MiseEnPaiement
    {
        return $this->miseEnPaiement;
    }



    public function getCentreCout (): ?CentreCout
    {
        return $this->centreCout;
    }



    public function getDomaineFonctionel (): ?DomaineFonctionnel
    {
        return $this->domaineFonctionnel;
    }



    public function getTypeIntervenant(): TypeIntervenant
    {
        return $this->typeIntervenant;
    }


    public function getIntervenant (): Intervenant
    {
        return $this->intervenant;
    }



    public function getAnnee (): Annee
    {
        return $this->annee;
    }



    public function getHeuresAPayerAA (): float
    {
        return $this->heuresAPayerAA;
    }



    public function getHeuresAPayerAC (): float
    {
        return $this->heuresAPayerAC;
    }



    public function getHeuresAPayer (): float
    {
        return round($this->heuresAPayerAA + $this->getHeuresAPayerAC(), 2);
    }



    public function getHeuresDemandeesAA (): float
    {
        return $this->heuresDemandeesAA;
    }



    public function getHeuresDemandeesAC (): float
    {
        return $this->heuresDemandeesAC;
    }



    public function getHeuresDemandees (): float
    {
        return round($this->heuresDemandeesAA + $this->heuresDemandeesAC, 2);
    }



    public function getHeuresPayeesAA (): float
    {
        return $this->heuresPayeesAA;
    }



    public function getHeuresPayeesAC (): float
    {
        return $this->heuresPayeesAC;
    }



    public function getHeuresPayees (): float
    {
        return round($this->heuresPayeesAA + $this->heuresPayeesAC, 2);
    }



    public function getPeriodeEnseignement (): ?Periode
    {
        return $this->periodeEnseignement;
    }
}