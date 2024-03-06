<?php

namespace Formule\Entity\Db;

use Formule\Entity\FormuleIntervenant;
use Intervenant\Entity\Db\Intervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

class FormuleResultatIntervenant extends FormuleIntervenant
{
    protected Intervenant $intervenant;

    protected float $total = 0.0;

    protected float $solde = 0.0;

    protected float $sousService = 0.0;


    // Résultats
    protected float $heuresServiceFi = 0.0;
    protected float $heuresServiceFa = 0.0;
    protected float $heuresServiceFc = 0.0;
    protected float $heuresServiceReferentiel = 0.0;

    protected float $heuresNonPayableFi = 0.0;
    protected float $heuresNonPayableFa = 0.0;
    protected float $heuresNonPayableFc = 0.0;
    protected float $heuresNonPayableReferentiel = 0.0;

    protected float $heuresComplFi = 0.0;
    protected float $heuresComplFa = 0.0;
    protected float $heuresComplFc = 0.0;
    protected float $heuresComplReferentiel = 0.0;
    protected float $heuresPrimes = 0.0;



    public function init(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): self
    {
        $this->intervenant = $intervenant;
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }

    /***********************************/
    /* Accésseurs générés par PhpStorm */
    /* Attention : getters uniquement  */
    /***********************************/

    public function getIntervenant(): Intervenant
    {
        return $this->intervenant;
    }



    public function getTotal(): float
    {
        return $this->total;
    }



    public function getSolde(): float
    {
        return $this->solde;
    }



    public function getSousService(): float
    {
        return $this->sousService;
    }



    public function getHeuresServiceFi(): float
    {
        return $this->heuresServiceFi;
    }



    public function getHeuresServiceFa(): float
    {
        return $this->heuresServiceFa;
    }



    public function getHeuresServiceFc(): float
    {
        return $this->heuresServiceFc;
    }



    public function getHeuresServiceReferentiel(): float
    {
        return $this->heuresServiceReferentiel;
    }



    public function getHeuresNonPayableFi(): float
    {
        return $this->heuresNonPayableFi;
    }



    public function getHeuresNonPayableFa(): float
    {
        return $this->heuresNonPayableFa;
    }



    public function getHeuresNonPayableFc(): float
    {
        return $this->heuresNonPayableFc;
    }



    public function getHeuresNonPayableReferentiel(): float
    {
        return $this->heuresNonPayableReferentiel;
    }



    public function getHeuresComplFi(): float
    {
        return $this->heuresComplFi;
    }



    public function getHeuresComplFa(): float
    {
        return $this->heuresComplFa;
    }



    public function getHeuresComplFc(): float
    {
        return $this->heuresComplFc;
    }



    public function getHeuresComplReferentiel(): float
    {
        return $this->heuresComplReferentiel;
    }



    public function getHeuresPrimes(): float
    {
        return $this->heuresPrimes;
    }

}