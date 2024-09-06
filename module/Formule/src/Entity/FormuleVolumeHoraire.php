<?php

namespace Formule\Entity;

use Enseignement\Entity\Db\Service;
use Enseignement\Entity\Db\VolumeHoraire;
use Referentiel\Entity\Db\ServiceReferentiel;
use Referentiel\Entity\Db\VolumeHoraireReferentiel;

class FormuleVolumeHoraire
{
    // Identifiants
    protected ?int $id = null;
    protected ?FormuleIntervenant $formuleIntervenant = null;
    protected null|int|VolumeHoraire $volumeHoraire = null;
    protected null|int|VolumeHoraireReferentiel $volumeHoraireReferentiel = null;
    protected null|int|Service $service = null;
    protected null|int|ServiceReferentiel $serviceReferentiel = null;

    // Paramètres globaux
    protected ?string $structureCode = null;
    protected ?string $typeInterventionCode = null;
    protected bool $structureUniv = false;
    protected bool $structureExterieur = false;
    protected bool $serviceStatutaire = true;
    protected bool $nonPayable = false;

    // Pondérations et heures
    protected float $tauxFi = 1.0;
    protected float $tauxFa = 0.0;
    protected float $tauxFc = 0.0;
    protected float $tauxServiceDu = 1.0; // en fonction des types d'intervention
    protected float $tauxServiceCompl = 1.0; // en fonction des types d'intervention
    protected float $ponderationServiceDu = 1.0;// relatif aux modulateurs
    protected float $ponderationServiceCompl = 1.0; // relatif aux modulateurs
    protected float $heures = 0.0; // heures réelles saisies

    // Paramètres spécifiques
    protected ?string $param1 = null;
    protected ?string $param2 = null;
    protected ?string $param3 = null;
    protected ?string $param4 = null;
    protected ?string $param5 = null;

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



    public function isStructureAffectation(): bool
    {
        return $this->formuleIntervenant->getStructureCode() === $this->getStructureCode();
    }



    public function setStructureAffectation(bool $structureAffectation)
    {
        /* Ne rien faire */
    }



    public function getTotal(): float
    {
        return $this->getHeuresServiceFi()
            + $this->getHeuresServiceFa()
            + $this->getHeuresServiceFc()
            + $this->getHeuresServiceReferentiel()
            + $this->getHeuresComplFi()
            + $this->getHeuresComplFa()
            + $this->getHeuresComplFc()
            + $this->getHeuresComplReferentiel()
            + $this->getHeuresPrimes();
    }



    /***********************************/
    /* Accésseurs générés par PhpStorm */
    /***********************************/


    public function getId(): ?int
    {
        return $this->id;
    }



    public function setId(?int $id): FormuleVolumeHoraire
    {
        $this->id = $id;
        return $this;
    }



    public function getFormuleIntervenant(): ?FormuleIntervenant
    {
        return $this->formuleIntervenant;
    }



    public function setFormuleIntervenant(?FormuleIntervenant $formuleIntervenant): FormuleVolumeHoraire
    {
        $this->formuleIntervenant = $formuleIntervenant;
        return $this;
    }



    public function getVolumeHoraire(): VolumeHoraire|int|null
    {
        return $this->volumeHoraire;
    }



    public function setVolumeHoraire(VolumeHoraire|int|null $volumeHoraire): FormuleVolumeHoraire
    {
        $this->volumeHoraire = $volumeHoraire;
        return $this;
    }



    public function getVolumeHoraireReferentiel(): VolumeHoraireReferentiel|int|null
    {
        return $this->volumeHoraireReferentiel;
    }



    public function setVolumeHoraireReferentiel(VolumeHoraireReferentiel|int|null $volumeHoraireReferentiel): FormuleVolumeHoraire
    {
        $this->volumeHoraireReferentiel = $volumeHoraireReferentiel;
        return $this;
    }



    public function getService(): Service|int|null
    {
        return $this->service;
    }



    public function setService(Service|int|null $service): FormuleVolumeHoraire
    {
        $this->service = $service;
        return $this;
    }



    public function getServiceReferentiel(): int|ServiceReferentiel|null
    {
        return $this->serviceReferentiel;
    }



    public function setServiceReferentiel(int|ServiceReferentiel|null $serviceReferentiel): FormuleVolumeHoraire
    {
        $this->serviceReferentiel = $serviceReferentiel;
        return $this;
    }



    public function getStructureCode(): ?string
    {
        return $this->structureCode;
    }



    public function setStructureCode(?string $structureCode): FormuleVolumeHoraire
    {
        $this->structureCode = $structureCode;
        return $this;
    }



    public function getTypeInterventionCode(): ?string
    {
        return $this->typeInterventionCode;
    }



    public function setTypeInterventionCode(?string $typeInterventionCode): FormuleVolumeHoraire
    {
        $this->typeInterventionCode = $typeInterventionCode;
        return $this;
    }



    public function isStructureUniv(): bool
    {
        return $this->structureUniv;
    }



    public function setStructureUniv(bool $structureUniv): FormuleVolumeHoraire
    {
        $this->structureUniv = $structureUniv;
        return $this;
    }



    public function isStructureExterieur(): bool
    {
        return $this->structureExterieur;
    }



    public function setStructureExterieur(bool $structureExterieur): FormuleVolumeHoraire
    {
        $this->structureExterieur = $structureExterieur;
        return $this;
    }



    public function isServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    public function setServiceStatutaire(bool $serviceStatutaire): FormuleVolumeHoraire
    {
        $this->serviceStatutaire = $serviceStatutaire;
        return $this;
    }



    public function isNonPayable(): bool
    {
        return $this->nonPayable;
    }



    public function setNonPayable(bool $nonPayable): FormuleVolumeHoraire
    {
        $this->nonPayable = $nonPayable;
        return $this;
    }



    public function getTauxFi(): float
    {
        return $this->tauxFi;
    }



    public function setTauxFi(float $tauxFi): FormuleVolumeHoraire
    {
        $this->tauxFi = $tauxFi;
        return $this;
    }



    public function getTauxFa(): float
    {
        return $this->tauxFa;
    }



    public function setTauxFa(float $tauxFa): FormuleVolumeHoraire
    {
        $this->tauxFa = $tauxFa;
        return $this;
    }



    public function getTauxFc(): float
    {
        return $this->tauxFc;
    }



    public function setTauxFc(float $tauxFc): FormuleVolumeHoraire
    {
        $this->tauxFc = $tauxFc;
        return $this;
    }



    public function getTauxServiceDu(): float
    {
        return $this->tauxServiceDu;
    }



    public function setTauxServiceDu(float $tauxServiceDu): FormuleVolumeHoraire
    {
        $this->tauxServiceDu = $tauxServiceDu;
        return $this;
    }



    public function getTauxServiceCompl(): float
    {
        return $this->tauxServiceCompl;
    }



    public function setTauxServiceCompl(float $tauxServiceCompl): FormuleVolumeHoraire
    {
        $this->tauxServiceCompl = $tauxServiceCompl;
        return $this;
    }



    public function getPonderationServiceDu(): float
    {
        return $this->ponderationServiceDu;
    }



    public function setPonderationServiceDu(float $ponderationServiceDu): FormuleVolumeHoraire
    {
        $this->ponderationServiceDu = $ponderationServiceDu;
        return $this;
    }



    public function getPonderationServiceCompl(): float
    {
        return $this->ponderationServiceCompl;
    }



    public function setPonderationServiceCompl(float $ponderationServiceCompl): FormuleVolumeHoraire
    {
        $this->ponderationServiceCompl = $ponderationServiceCompl;
        return $this;
    }



    public function getHeures(): float
    {
        return $this->heures;
    }



    public function setHeures(float $heures): FormuleVolumeHoraire
    {
        $this->heures = $heures;
        return $this;
    }



    public function getParam1(): ?string
    {
        return $this->param1;
    }



    public function setParam1(?string $param1): FormuleVolumeHoraire
    {
        $this->param1 = $param1;
        return $this;
    }



    public function getParam2(): ?string
    {
        return $this->param2;
    }



    public function setParam2(?string $param2): FormuleVolumeHoraire
    {
        $this->param2 = $param2;
        return $this;
    }



    public function getParam3(): ?string
    {
        return $this->param3;
    }



    public function setParam3(?string $param3): FormuleVolumeHoraire
    {
        $this->param3 = $param3;
        return $this;
    }



    public function getParam4(): ?string
    {
        return $this->param4;
    }



    public function setParam4(?string $param4): FormuleVolumeHoraire
    {
        $this->param4 = $param4;
        return $this;
    }



    public function getParam5(): ?string
    {
        return $this->param5;
    }



    public function setParam5(?string $param5): FormuleVolumeHoraire
    {
        $this->param5 = $param5;
        return $this;
    }



    public function getHeuresServiceFi(): float
    {
        return $this->heuresServiceFi;
    }



    public function setHeuresServiceFi(float $heuresServiceFi): FormuleVolumeHoraire
    {
        $this->heuresServiceFi = $heuresServiceFi;
        return $this;
    }



    public function getHeuresServiceFa(): float
    {
        return $this->heuresServiceFa;
    }



    public function setHeuresServiceFa(float $heuresServiceFa): FormuleVolumeHoraire
    {
        $this->heuresServiceFa = $heuresServiceFa;
        return $this;
    }



    public function getHeuresServiceFc(): float
    {
        return $this->heuresServiceFc;
    }



    public function setHeuresServiceFc(float $heuresServiceFc): FormuleVolumeHoraire
    {
        $this->heuresServiceFc = $heuresServiceFc;
        return $this;
    }



    public function getHeuresServiceReferentiel(): float
    {
        return $this->heuresServiceReferentiel;
    }



    public function setHeuresServiceReferentiel(float $heuresServiceReferentiel): FormuleVolumeHoraire
    {
        $this->heuresServiceReferentiel = $heuresServiceReferentiel;
        return $this;
    }



    public function getHeuresNonPayableFi(): float
    {
        return $this->heuresNonPayableFi;
    }



    public function setHeuresNonPayableFi(float $heuresNonPayableFi): FormuleVolumeHoraire
    {
        $this->heuresNonPayableFi = $heuresNonPayableFi;
        return $this;
    }



    public function getHeuresNonPayableFa(): float
    {
        return $this->heuresNonPayableFa;
    }



    public function setHeuresNonPayableFa(float $heuresNonPayableFa): FormuleVolumeHoraire
    {
        $this->heuresNonPayableFa = $heuresNonPayableFa;
        return $this;
    }



    public function getHeuresNonPayableFc(): float
    {
        return $this->heuresNonPayableFc;
    }



    public function setHeuresNonPayableFc(float $heuresNonPayableFc): FormuleVolumeHoraire
    {
        $this->heuresNonPayableFc = $heuresNonPayableFc;
        return $this;
    }



    public function getHeuresNonPayableReferentiel(): float
    {
        return $this->heuresNonPayableReferentiel;
    }



    public function setHeuresNonPayableReferentiel(float $heuresNonPayableReferentiel): FormuleVolumeHoraire
    {
        $this->heuresNonPayableReferentiel = $heuresNonPayableReferentiel;
        return $this;
    }



    public function getHeuresComplFi(): float
    {
        return $this->heuresComplFi;
    }



    public function setHeuresComplFi(float $heuresComplFi): FormuleVolumeHoraire
    {
        $this->heuresComplFi = $heuresComplFi;
        return $this;
    }



    public function getHeuresComplFa(): float
    {
        return $this->heuresComplFa;
    }



    public function setHeuresComplFa(float $heuresComplFa): FormuleVolumeHoraire
    {
        $this->heuresComplFa = $heuresComplFa;
        return $this;
    }



    public function getHeuresComplFc(): float
    {
        return $this->heuresComplFc;
    }



    public function setHeuresComplFc(float $heuresComplFc): FormuleVolumeHoraire
    {
        $this->heuresComplFc = $heuresComplFc;
        return $this;
    }



    public function getHeuresPrimes(): float
    {
        return $this->heuresPrimes;
    }



    public function setHeuresPrimes(float $heuresPrimes): FormuleVolumeHoraire
    {
        $this->heuresPrimes = $heuresPrimes;
        return $this;
    }



    public function getHeuresComplReferentiel(): float
    {
        return $this->heuresComplReferentiel;
    }



    public function setHeuresComplReferentiel(float $heuresComplReferentiel): FormuleVolumeHoraire
    {
        $this->heuresComplReferentiel = $heuresComplReferentiel;
        return $this;
    }


}