<?php

namespace Formule\Entity\Db;

class FormuleTestVolumeHoraire
{
    use FormuleTestIntervenantAwareTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var bool
     */
    private $referentiel = false;

    /**
     * @var bool
     */
    private $serviceStatutaire = true;

    /**
     * @var string
     */
    private $typeInterventionCode;

    /**
     * @var string|null
     */
    private $structureCode;

    /**
     * @var float
     */
    private $tauxFi = 1;

    /**
     * @var float
     */
    private $tauxFa = 0;

    /**
     * @var float
     */
    private $tauxFc = 0;

    /**
     * @var float
     */
    private $ponderationServiceDu = 1;

    /**
     * @var float
     */
    private $ponderationServiceCompl = 1;

    /**
     * @var string
     */
    private $param1;

    /**
     * @var string
     */
    private $param2;

    /**
     * @var string
     */
    private $param3;

    /**
     * @var string
     */
    private $param4;

    /**
     * @var string
     */
    private $param5;

    /**
     * @var float
     */
    private $heures = 0;

    /**
     * @var float
     */
    private $aServiceFi;

    /**
     * @var float
     */
    private $aServiceFa;

    /**
     * @var float
     */
    private $aServiceFc;

    /**
     * @var float
     */
    private $aServiceReferentiel;

    /**
     * @var float
     */
    private $aHeuresComplFi;

    /**
     * @var float
     */
    private $aHeuresComplFa;

    /**
     * @var float
     */
    private $aHeuresComplFc;

    /**
     * @var float
     */
    private $aHeuresComplFcMajorees;

    /**
     * @var float
     */
    private $aHeuresComplReferentiel;

    /**
     * @var float
     */
    private $cServiceFi;

    /**
     * @var float
     */
    private $cServiceFa;

    /**
     * @var float
     */
    private $cServiceFc;

    /**
     * @var float
     */
    private $cServiceReferentiel;

    /**
     * @var float
     */
    private $cHeuresComplFi;

    /**
     * @var float
     */
    private $cHeuresComplFa;

    /**
     * @var float
     */
    private $cHeuresComplFc;

    /**
     * @var float
     */
    private $cHeuresComplFcMajorees;

    /**
     * @var float
     */
    private $cHeuresComplReferentiel;

    /**
     * @var string
     */
    private $debugInfo;



    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }



    /**
     * @return bool
     */
    public function getReferentiel()
    {
        return $this->referentiel;
    }



    /**
     * @param bool $referentiel
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setReferentiel($referentiel): FormuleTestVolumeHoraire
    {
        $this->referentiel = $referentiel;

        return $this;
    }



    /**
     * @return bool
     */
    public function getServiceStatutaire(): bool
    {
        return $this->serviceStatutaire;
    }



    /**
     * @param bool $serviceStatutaire
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setServiceStatutaire(bool $serviceStatutaire): FormuleTestVolumeHoraire
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }



    /**
     * @return string
     */
    public function getTypeInterventionCode()
    {
        return $this->typeInterventionCode;
    }



    /**
     * @param string $typeInterventionCode
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setTypeInterventionCode($typeInterventionCode): FormuleTestVolumeHoraire
    {
        $this->typeInterventionCode = $typeInterventionCode;

        return $this;
    }



    /**
     * @return string|null
     */
    public function getStructureCode(): ?string
    {
        return $this->structureCode;
    }



    /**
     * @param string|null $structureCode
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setStructureCode(?string $structureCode): FormuleTestVolumeHoraire
    {
        $this->structureCode = $structureCode;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxFi(): float
    {
        return $this->tauxFi;
    }



    /**
     * @param float $tauxFi
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setTauxFi(float $tauxFi): FormuleTestVolumeHoraire
    {
        $this->tauxFi = $tauxFi;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxFa(): float
    {
        return $this->tauxFa;
    }



    /**
     * @param float $tauxFa
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setTauxFa(float $tauxFa): FormuleTestVolumeHoraire
    {
        $this->tauxFa = $tauxFa;

        return $this;
    }



    /**
     * @return float
     */
    public function getTauxFc(): float
    {
        return $this->tauxFc;
    }



    /**
     * @param float $tauxFc
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setTauxFc(float $tauxFc): FormuleTestVolumeHoraire
    {
        $this->tauxFc = $tauxFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getPonderationServiceDu(): float
    {
        return $this->ponderationServiceDu;
    }



    /**
     * @param float $ponderationServiceDu
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setPonderationServiceDu(float $ponderationServiceDu): FormuleTestVolumeHoraire
    {
        $this->ponderationServiceDu = $ponderationServiceDu;

        return $this;
    }



    /**
     * @return float
     */
    public function getPonderationServiceCompl(): float
    {
        return $this->ponderationServiceCompl;
    }



    /**
     * @param float $ponderationServiceCompl
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setPonderationServiceCompl(float $ponderationServiceCompl): FormuleTestVolumeHoraire
    {
        $this->ponderationServiceCompl = $ponderationServiceCompl;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam1()
    {
        return $this->param1;
    }



    /**
     * @param string $param1
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setParam1($param1): FormuleTestVolumeHoraire
    {
        $this->param1 = $param1;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam2()
    {
        return $this->param2;
    }



    /**
     * @param string $param2
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setParam2($param2): FormuleTestVolumeHoraire
    {
        $this->param2 = $param2;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam3()
    {
        return $this->param3;
    }



    /**
     * @param string $param3
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setParam3($param3): FormuleTestVolumeHoraire
    {
        $this->param3 = $param3;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam4()
    {
        return $this->param4;
    }



    /**
     * @param string $param4
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setParam4($param4): FormuleTestVolumeHoraire
    {
        $this->param4 = $param4;

        return $this;
    }



    /**
     * @return string
     */
    public function getParam5()
    {
        return $this->param5;
    }



    /**
     * @param string $param5
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setParam5($param5): FormuleTestVolumeHoraire
    {
        $this->param5 = $param5;

        return $this;
    }



    /**
     * @return float
     */
    public function getHeures()
    {
        return $this->heures;
    }



    /**
     * @param float $heures
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setHeures(float $heures): FormuleTestVolumeHoraire
    {
        $this->heures = $heures;

        return $this;
    }



    /**
     * @return float
     */
    public function getAServiceFi()
    {
        return $this->aServiceFi;
    }



    /**
     * @param float $aServiceFi
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAServiceFi($aServiceFi): FormuleTestVolumeHoraire
    {
        $this->aServiceFi = $aServiceFi;

        return $this;
    }



    /**
     * @return float
     */
    public function getAServiceFa()
    {
        return $this->aServiceFa;
    }



    /**
     * @param float $aServiceFa
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAServiceFa($aServiceFa): FormuleTestVolumeHoraire
    {
        $this->aServiceFa = $aServiceFa;

        return $this;
    }



    /**
     * @return float
     */
    public function getAServiceFc()
    {
        return $this->aServiceFc;
    }



    /**
     * @param float $aServiceFc
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAServiceFc($aServiceFc): FormuleTestVolumeHoraire
    {
        $this->aServiceFc = $aServiceFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getAServiceReferentiel()
    {
        return $this->aServiceReferentiel;
    }



    /**
     * @param float $aServiceReferentiel
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAServiceReferentiel($aServiceReferentiel): FormuleTestVolumeHoraire
    {
        $this->aServiceReferentiel = $aServiceReferentiel;

        return $this;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFi()
    {
        return $this->aHeuresComplFi;
    }



    /**
     * @param float $aHeuresComplFi
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAHeuresComplFi($aHeuresComplFi): FormuleTestVolumeHoraire
    {
        $this->aHeuresComplFi = $aHeuresComplFi;

        return $this;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFa()
    {
        return $this->aHeuresComplFa;
    }



    /**
     * @param float $aHeuresComplFa
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAHeuresComplFa($aHeuresComplFa): FormuleTestVolumeHoraire
    {
        $this->aHeuresComplFa = $aHeuresComplFa;

        return $this;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFc()
    {
        return $this->aHeuresComplFc;
    }



    /**
     * @param float $aHeuresComplFc
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAHeuresComplFc($aHeuresComplFc): FormuleTestVolumeHoraire
    {
        $this->aHeuresComplFc = $aHeuresComplFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getAHeuresComplFcMajorees()
    {
        return $this->aHeuresComplFcMajorees;
    }



    /**
     * @param float $aHeuresComplFcMajorees
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAHeuresComplFcMajorees($aHeuresComplFcMajorees): FormuleTestVolumeHoraire
    {
        $this->aHeuresComplFcMajorees = $aHeuresComplFcMajorees;

        return $this;
    }



    /**
     * @return float
     */
    public function getAHeuresComplReferentiel()
    {
        return $this->aHeuresComplReferentiel;
    }



    /**
     * @param float $aHeuresComplReferentiel
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setAHeuresComplReferentiel($aHeuresComplReferentiel): FormuleTestVolumeHoraire
    {
        $this->aHeuresComplReferentiel = $aHeuresComplReferentiel;

        return $this;
    }



    /**
     * @return float
     */
    public function getCServiceFi()
    {
        return $this->cServiceFi;
    }



    /**
     * @param float $cServiceFi
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCServiceFi(float $cServiceFi): FormuleTestVolumeHoraire
    {
        $this->cServiceFi = $cServiceFi;

        return $this;
    }



    /**
     * @return float
     */
    public function getCServiceFa()
    {
        return $this->cServiceFa;
    }



    /**
     * @param float $cServiceFa
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCServiceFa(float $cServiceFa): FormuleTestVolumeHoraire
    {
        $this->cServiceFa = $cServiceFa;

        return $this;
    }



    /**
     * @return float
     */
    public function getCServiceFc()
    {
        return $this->cServiceFc;
    }



    /**
     * @param float $cServiceFc
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCServiceFc(float $cServiceFc): FormuleTestVolumeHoraire
    {
        $this->cServiceFc = $cServiceFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getCServiceReferentiel()
    {
        return $this->cServiceReferentiel;
    }



    /**
     * @param float $cServiceReferentiel
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCServiceReferentiel(float $cServiceReferentiel): FormuleTestVolumeHoraire
    {
        $this->cServiceReferentiel = $cServiceReferentiel;

        return $this;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFi()
    {
        return $this->cHeuresComplFi;
    }



    /**
     * @param float $cHeuresComplFi
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCHeuresComplFi(float $cHeuresComplFi): FormuleTestVolumeHoraire
    {
        $this->cHeuresComplFi = $cHeuresComplFi;

        return $this;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFa()
    {
        return $this->cHeuresComplFa;
    }



    /**
     * @param float $cHeuresComplFa
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCHeuresComplFa(float $cHeuresComplFa): FormuleTestVolumeHoraire
    {
        $this->cHeuresComplFa = $cHeuresComplFa;

        return $this;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFc()
    {
        return $this->cHeuresComplFc;
    }



    /**
     * @param float $cHeuresComplFc
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCHeuresComplFc(float $cHeuresComplFc): FormuleTestVolumeHoraire
    {
        $this->cHeuresComplFc = $cHeuresComplFc;

        return $this;
    }



    /**
     * @return float
     */
    public function getCHeuresComplFcMajorees()
    {
        return $this->cHeuresComplFcMajorees;
    }



    /**
     * @param float $cHeuresComplFcMajorees
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCHeuresComplFcMajorees(float $cHeuresComplFcMajorees): FormuleTestVolumeHoraire
    {
        $this->cHeuresComplFcMajorees = $cHeuresComplFcMajorees;

        return $this;
    }



    /**
     * @return float
     */
    public function getCHeuresComplReferentiel()
    {
        return $this->cHeuresComplReferentiel;
    }



    /**
     * @param float $cHeuresComplReferentiel
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setCHeuresComplReferentiel(float $cHeuresComplReferentiel): FormuleTestVolumeHoraire
    {
        $this->cHeuresComplReferentiel = $cHeuresComplReferentiel;

        return $this;
    }



    /**
     * @return string
     */
    public function getDebugInfo()
    {
        return $this->debugInfo;
    }



    /**
     * @param string $debugInfo
     *
     * @return FormuleTestVolumeHoraire
     */
    public function setDebugInfo($debugInfo): FormuleTestVolumeHoraire
    {
        $this->debugInfo = $debugInfo;

        return $this;
    }

}
