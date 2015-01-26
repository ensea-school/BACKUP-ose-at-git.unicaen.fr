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
     * @var float
     */
    private $ponderationServiceDu;

    /**
     * @var float
     */
    private $ponderationServiceCompl;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Service
     */
    private $service;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\FormuleIntervenant
     */
    private $formuleIntervenant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleVolumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set id
     *
     * @param integer $id
     * @return FormuleService
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
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
     * Add formuleVolumeHoraire
     *
     * @param \Application\Entity\Db\FormuleVolumeHoraire $formuleVolumeHoraire
     * @return FormuleService
     */
    public function addFormuleVolumeHoraire(\Application\Entity\Db\FormuleVolumeHoraire $formuleVolumeHoraire)
    {
        $this->formuleVolumeHoraire[] = $formuleVolumeHoraire;

        return $this;
    }

    /**
     * Remove formuleVolumeHoraire
     *
     * @param \Application\Entity\Db\FormuleVolumeHoraire $formuleVolumeHoraire
     */
    public function removeFormuleVolumeHoraire(\Application\Entity\Db\FormuleVolumeHoraire $formuleVolumeHoraire)
    {
        $this->formuleVolumeHoraire->removeElement($formuleVolumeHoraire);
    }

    /**
     * Get formuleVolumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleVolumeHoraire()
    {
        return $this->formuleVolumeHoraire;
    }

    public function getHeures(TypeVolumeHoraire $typeVolumeHoraire=null, EtatVolumeHoraire $etatVolumeHoraire=null, TypeIntervention $typeIntervention=null)
    {
        $heures = 0;
        $vhs = $this->getFormuleVolumeHoraire();
        foreach( $vhs as $vh ){
            /* @var $vh FormuleVolumeHoraire */
            $ok = true;
            if ($ok && $typeVolumeHoraire !== null && $vh->getTypeVolumeHoraire() !== $typeVolumeHoraire) $ok = false;
            if ($ok && $etatVolumeHoraire !== null && $vh->getEtatVolumeHoraire() !== $etatVolumeHoraire) $ok = false;
            if ($ok && $typeIntervention  !== null && $vh->getTypeIntervention()  !== $typeIntervention)  $ok = false;
            if ($ok) $heures += $vh->getHeures ();
        }
        return $heures;
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

    /**
     * Set formuleIntervenant
     *
     * @param \Application\Entity\Db\FormuleIntervenant $formuleIntervenant
     * @return FormuleService
     */
    public function setFormuleIntervenant(\Application\Entity\Db\FormuleIntervenant $formuleIntervenant = null)
    {
        $this->formuleIntervenant = $formuleIntervenant;

        return $this;
    }

    /**
     * Get formuleIntervenant
     *
     * @return \Application\Entity\Db\FormuleIntervenant 
     */
    public function getFormuleIntervenant()
    {
        return $this->formuleIntervenant;
    }
}
