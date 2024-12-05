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
    protected float $heuresServiceFi          = 0.0;
    protected float $heuresServiceFa          = 0.0;
    protected float $heuresServiceFc          = 0.0;
    protected float $heuresServiceReferentiel = 0.0;

    protected float $heuresNonPayableFi          = 0.0;
    protected float $heuresNonPayableFa          = 0.0;
    protected float $heuresNonPayableFc          = 0.0;
    protected float $heuresNonPayableReferentiel = 0.0;

    protected float $heuresComplFi          = 0.0;
    protected float $heuresComplFa          = 0.0;
    protected float $heuresComplFc          = 0.0;
    protected float $heuresComplReferentiel = 0.0;
    protected float $heuresPrimes           = 0.0;



    public function init(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire): self
    {
        $this->intervenant       = $intervenant;
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



    public function setIntervenant(Intervenant $intervenant): FormuleResultatIntervenant
    {
        $this->intervenant = $intervenant;
        return $this;
    }



    public function setTotal(float $total): FormuleResultatIntervenant
    {
        $this->total = $total;
        return $this;
    }



    public function setSolde(float $solde): FormuleResultatIntervenant
    {
        $this->solde = $solde;
        return $this;
    }



    public function setSousService(float $sousService): FormuleResultatIntervenant
    {
        $this->sousService = $sousService;
        return $this;
    }



    public function setHeuresServiceFi(float $heuresServiceFi): FormuleResultatIntervenant
    {
        $this->heuresServiceFi = $heuresServiceFi;
        return $this;
    }



    public function setHeuresServiceFa(float $heuresServiceFa): FormuleResultatIntervenant
    {
        $this->heuresServiceFa = $heuresServiceFa;
        return $this;
    }



    public function setHeuresServiceFc(float $heuresServiceFc): FormuleResultatIntervenant
    {
        $this->heuresServiceFc = $heuresServiceFc;
        return $this;
    }



    public function setHeuresServiceReferentiel(float $heuresServiceReferentiel): FormuleResultatIntervenant
    {
        $this->heuresServiceReferentiel = $heuresServiceReferentiel;
        return $this;
    }



    public function setHeuresNonPayableFi(float $heuresNonPayableFi): FormuleResultatIntervenant
    {
        $this->heuresNonPayableFi = $heuresNonPayableFi;
        return $this;
    }



    public function setHeuresNonPayableFa(float $heuresNonPayableFa): FormuleResultatIntervenant
    {
        $this->heuresNonPayableFa = $heuresNonPayableFa;
        return $this;
    }



    public function setHeuresNonPayableFc(float $heuresNonPayableFc): FormuleResultatIntervenant
    {
        $this->heuresNonPayableFc = $heuresNonPayableFc;
        return $this;
    }



    public function setHeuresNonPayableReferentiel(float $heuresNonPayableReferentiel): FormuleResultatIntervenant
    {
        $this->heuresNonPayableReferentiel = $heuresNonPayableReferentiel;
        return $this;
    }



    public function setHeuresComplFi(float $heuresComplFi): FormuleResultatIntervenant
    {
        $this->heuresComplFi = $heuresComplFi;
        return $this;
    }



    public function setHeuresComplFa(float $heuresComplFa): FormuleResultatIntervenant
    {
        $this->heuresComplFa = $heuresComplFa;
        return $this;
    }



    public function setHeuresComplFc(float $heuresComplFc): FormuleResultatIntervenant
    {
        $this->heuresComplFc = $heuresComplFc;
        return $this;
    }



    public function setHeuresComplReferentiel(float $heuresComplReferentiel): FormuleResultatIntervenant
    {
        $this->heuresComplReferentiel = $heuresComplReferentiel;
        return $this;
    }



    public function setHeuresPrimes(float $heuresPrimes): FormuleResultatIntervenant
    {
        $this->heuresPrimes = $heuresPrimes;
        return $this;
    }



    public function getHeures(?string $categorie = null, ?string $type = null): float
    {
        $heures = 0.0;

        $functions = [
            'getHeuresServiceFi'             => ['categorie' => 'service', 'types' => ['fi', 'enseignement']],
            'getHeuresServiceFa'             => ['categorie' => 'service', 'types' => ['fa', 'enseignement']],
            'getHeuresServiceFc'             => ['categorie' => 'service', 'types' => ['fc', 'enseignement']],
            'getHeuresServiceReferentiel'    => ['categorie' => 'service', 'types' => ['referentiel']],
            'getHeuresComplFi'               => ['categorie' => 'compl', 'types' => ['fi', 'enseignement']],
            'getHeuresComplFa'               => ['categorie' => 'compl', 'types' => ['fa', 'enseignement']],
            'getHeuresComplFc'               => ['categorie' => 'compl', 'types' => ['fc', 'enseignement']],
            'getHeuresComplReferentiel'      => ['categorie' => 'compl', 'types' => ['referentiel']],
            'getHeuresPrimes'                => ['categorie' => 'primes', 'types' => []],
            'getHeuresNonPayableFi'          => ['categorie' => 'non-payable', 'types' => ['fi', 'enseignement']],
            'getHeuresNonPayableFa'          => ['categorie' => 'non-payable', 'types' => ['fa', 'enseignement']],
            'getHeuresNonPayableFc'          => ['categorie' => 'non-payable', 'types' => ['fc', 'enseignement']],
            'getHeuresNonPayableReferentiel' => ['categorie' => 'non-payable', 'types' => ['referentiel']],
        ];

        foreach ($functions as $fnc => $config) {
            if (null !== $categorie && $config['categorie'] !== $categorie) {
                continue;
            }
            if (null !== $type && !in_array($type, $config['types'])) {
                continue;
            }
            $heures += $this->$fnc();
        }

        return round($heures, 2);
    }

}