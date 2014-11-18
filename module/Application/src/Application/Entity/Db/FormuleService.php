<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleService
 */
class FormuleService
{
    /**
     * @var float
     */
    private $ponderationServiceCompl;

    /**
     * @var float
     */
    private $ponderationServiceDu;

    /**
     * @var float
     */
    private $tauxFa;

    /**
     * @var float
     */
    private $tauxFc;

    /**
     * @var float
     */
    private $tauxFi;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Set ponderationServiceCompl
     *
     * @param float $ponderationServiceCompl
     * @return FormuleService
     */
    public function setPonderationServiceCompl($ponderationServiceCompl)
    {
        $this->ponderationServiceCompl = $ponderationServiceCompl;

        return $this;
    }

    /**
     * Get ponderationServiceCompl
     *
     * @return float 
     */
    public function getPonderationServiceCompl()
    {
        return $this->ponderationServiceCompl;
    }

    /**
     * Set ponderationServiceDu
     *
     * @param float $ponderationServiceDu
     * @return FormuleService
     */
    public function setPonderationServiceDu($ponderationServiceDu)
    {
        $this->ponderationServiceDu = $ponderationServiceDu;

        return $this;
    }

    /**
     * Get ponderationServiceDu
     *
     * @return float 
     */
    public function getPonderationServiceDu()
    {
        return $this->ponderationServiceDu;
    }

    /**
     * Set tauxFa
     *
     * @param float $tauxFa
     * @return FormuleService
     */
    public function setTauxFa($tauxFa)
    {
        $this->tauxFa = $tauxFa;

        return $this;
    }

    /**
     * Get tauxFa
     *
     * @return float 
     */
    public function getTauxFa()
    {
        return $this->tauxFa;
    }

    /**
     * Set tauxFc
     *
     * @param float $tauxFc
     * @return FormuleService
     */
    public function setTauxFc($tauxFc)
    {
        $this->tauxFc = $tauxFc;

        return $this;
    }

    /**
     * Get tauxFc
     *
     * @return float 
     */
    public function getTauxFc()
    {
        return $this->tauxFc;
    }

    /**
     * Set tauxFi
     *
     * @param float $tauxFi
     * @return FormuleService
     */
    public function setTauxFi($tauxFi)
    {
        $this->tauxFi = $tauxFi;

        return $this;
    }

    /**
     * Get tauxFi
     *
     * @return float 
     */
    public function getTauxFi()
    {
        return $this->tauxFi;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set service
     *
     * @param \Application\Entity\Db\Service $service
     * @return FormuleService
     */
    public function setService(\Application\Entity\Db\Service $service = null)
    {
        $this->service = $service;

        return $this;
    }

    /**
     * Get service
     *
     * @return \Application\Entity\Db\Service 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return FormuleService
     */
    public function setIntervenant(\Application\Entity\Db\Intervenant $intervenant = null)
    {
        $this->intervenant = $intervenant;

        return $this;
    }

    /**
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
    }

    /**
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return FormuleService
     */
    public function setAnnee(\Application\Entity\Db\Annee $annee = null)
    {
        $this->annee = $annee;

        return $this;
    }

    /**
     * Get annee
     *
     * @return \Application\Entity\Db\Annee 
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
