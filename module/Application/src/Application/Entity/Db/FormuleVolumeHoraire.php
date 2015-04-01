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
     * Get heures
     *
     * @return float 
     */
    public function getHeures()
    {
        return $this->heures;
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
     * Get tauxServiceDu
     *
     * @return float 
     */
    public function getTauxServiceDu()
    {
        return $this->tauxServiceDu;
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
     * Get volumeHoraire
     *
     * @return \Application\Entity\Db\VolumeHoraire 
     */
    public function getVolumeHoraire()
    {
        return $this->volumeHoraire;
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
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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
     * Get typeVolumeHoraire
     *
     * @return \Application\Entity\Db\TypeVolumeHoraire 
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
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
     * Get formuleService
     *
     * @return \Application\Entity\Db\FormuleService 
     */
    public function getFormuleService()
    {
        return $this->formuleService;
    }
}
