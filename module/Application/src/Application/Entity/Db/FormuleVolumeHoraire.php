<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleVolumeHoraire
 */
class FormuleVolumeHoraire
{
    /**
     * @var float
     */
    private $heures;

    /**
     * @var float
     */
    private $tauxServiceCompl;

    /**
     * @var float
     */
    private $tauxServiceDu;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\VolumeHoraire
     */
    private $volumeHoraire;

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
     * @var \Application\Entity\Db\TypeIntervention
     */
    private $typeIntervention;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\EtatVolumeHoraire
     */
    private $etatVolumeHoraire;

    /**
     * @var \Application\Entity\Db\FormuleService
     */
    private $formuleService;


    /**
     * Set heures
     *
     * @param float $heures
     * @return FormuleVolumeHoraire
     */
    public function setHeures($heures)
    {
        $this->heures = $heures;

        return $this;
    }

    /**
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
    }

    /**
     * Set tauxServiceCompl
     *
     * @param float $tauxServiceCompl
     * @return FormuleVolumeHoraire
     */
    public function setTauxServiceCompl($tauxServiceCompl)
    {
        $this->tauxServiceCompl = $tauxServiceCompl;

        return $this;
    }

    /**
     * Get tauxServiceCompl
     *
     * @return float 
     */
    public function getTauxServiceCompl()
    {
        return $this->tauxServiceCompl;
    }

    /**
     * Set tauxServiceDu
     *
     * @param float $tauxServiceDu
     * @return FormuleVolumeHoraire
     */
    public function setTauxServiceDu($tauxServiceDu)
    {
        $this->tauxServiceDu = $tauxServiceDu;

        return $this;
    }

    /**
     * Get tauxServiceDu
     *
     * @return float 
     */
    public function getTauxServiceDu()
    {
        return $this->tauxServiceDu;
    }

    /**
     * Set id
     *
     * @param integer $id
     * @return FormuleVolumeHoraire
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
     * Set volumeHoraire
     *
     * @param \Application\Entity\Db\VolumeHoraire $volumeHoraire
     * @return FormuleVolumeHoraire
     */
    public function setVolumeHoraire(\Application\Entity\Db\VolumeHoraire $volumeHoraire = null)
    {
        $this->volumeHoraire = $volumeHoraire;

        return $this;
    }

    /**
     * Get volumeHoraire
     *
     * @return \Application\Entity\Db\VolumeHoraire 
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
    }

    /**
     * Set service
     *
     * @param \Application\Entity\Db\Service $service
     * @return FormuleVolumeHoraire
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
     * @return FormuleVolumeHoraire
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
     * @return FormuleVolumeHoraire
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
     * Set typeIntervention
     *
     * @param \Application\Entity\Db\TypeIntervention $typeIntervention
     * @return FormuleVolumeHoraire
     */
    public function setTypeIntervention(\Application\Entity\Db\TypeIntervention $typeIntervention = null)
    {
        $this->typeIntervention = $typeIntervention;

        return $this;
    }

    /**
     * Get typeIntervention
     *
     * @return \Application\Entity\Db\TypeIntervention 
     */
    public function getTypeIntervention()
    {
        return $this->typeIntervention;
    }

    /**
     * Set typeVolumeHoraire
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     * @return FormuleVolumeHoraire
     */
    public function setTypeVolumeHoraire(\Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire = null)
    {
        $this->typeVolumeHoraire = $typeVolumeHoraire;

        return $this;
    }

    /**
     * Get typeVolumeHoraire
     *
     * @return \Application\Entity\Db\TypeVolumeHoraire 
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }

    /**
     * Set etatVolumeHoraire
     *
     * @param \Application\Entity\Db\EtatVolumeHoraire $etatVolumeHoraire
     * @return FormuleVolumeHoraire
     */
    public function setEtatVolumeHoraire(\Application\Entity\Db\EtatVolumeHoraire $etatVolumeHoraire = null)
    {
        $this->etatVolumeHoraire = $etatVolumeHoraire;

        return $this;
    }

    /**
     * Get etatVolumeHoraire
     *
     * @return \Application\Entity\Db\EtatVolumeHoraire 
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }

    /**
     * Set formuleService
     *
     * @param \Application\Entity\Db\FormuleService $formuleService
     * @return FormuleVolumeHoraire
     */
    public function setFormuleService(\Application\Entity\Db\FormuleService $formuleService = null)
    {
        $this->formuleService = $formuleService;

        return $this;
    }

    /**
     * Get formuleService
     *
     * @return \Application\Entity\Db\FormuleService 
     */
    public function getFormuleService()
    {
        return $this->formuleService;
    }
}
