<?php

namespace Application\Entity\Db;

/**
 * FormuleResultat
 */
class FormuleResultat
{

    /**
     * @var float
     */
    private $enseignements;

    /**
     * @var float
     */
    private $heuresSolde;

    /**
     * @var float
     */
    private $heuresComplFc;

    /**
     * @var float
     */
    private $heuresComplFa;

    /**
     * @var float
     */
    private $heuresComplFi;

    /**
     * @var float
     */
    private $heuresComplReferentiel;

    /**
     * @var float
     */
    private $heuresComplTotal;

    /**
     * @var float
     */
    private $service;

    /**
     * @var float
     */
    private $referentiel;

    /**
     * @var float
     */
    private $serviceAssure;

    /**
     * @var float
     */
    private $serviceDu;

    /**
     * @var float
     */
    private $sousService;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatService;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatReferentiel;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\EtatVolumeHoraire
     */
    private $etatVolumeHoraire;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleResultatService = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }


    public function init( Intervenant $intervenant, Annee $annee, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        $this->intervenant = $intervenant;
        $this->annee = $annee;
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        $this->etatVolumeHoraire = $etatVolumeHoraire;
    }


    /**
     * Get aPayer
     *
     * @return float 
     */
    public function getAPayer()
    {
        return $this->aPayer;
    }

    /**
     * Get enseignements
     *
     * @return float 
     */
    public function getEnseignements()
    {
        return $this->enseignements;
    }

    /**
     * Get heuresSolde
     *
     * @return float
     */
    public function getHeuresSolde()
    {
        return $this->heuresSolde;
    }

    /**
     * Get heuresComplFc
     *
     * @return float 
     */
    public function getHeuresComplFc()
    {
        return $this->heuresComplFc;
    }

    /**
     * Get heuresComplFa
     *
     * @return float
     */
    public function getHeuresComplFa()
    {
        return $this->heuresComplFa;
    }

    /**
     * Get heuresComplFi
     *
     * @return float 
     */
    public function getHeuresComplFi()
    {
        return $this->heuresComplFi;
    }

    /**
     * Get heuresComplReferentiel
     *
     * @return float 
     */
    public function getHeuresComplReferentiel()
    {
        return $this->heuresComplReferentiel;
    }

    /**
     * Get heuresComplTotal
     *
     * @return float
     */
    public function getHeuresComplTotal()
    {
        return $this->heuresComplTotal;
    }

    /**
     * Get service
     *
     * @return float 
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * Get referentiel
     *
     * @return float 
     */
    public function getReferentiel()
    {
        return $this->referentiel;
    }

    /**
     * Get serviceAssure
     *
     * @return float 
     */
    public function getServiceAssure()
    {
        return $this->serviceAssure;
    }

    /**
     * Get serviceDu
     *
     * @return float 
     */
    public function getServiceDu()
    {
        return $this->serviceDu;
    }

    /**
     * Get sousService
     *
     * @return float 
     */
    public function getSousService()
    {
        return $this->sousService;
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
     * Get formuleResultatService
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatService()
    {
        return $this->formuleResultatService;
    }

    /**
     * Get formuleResultatReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatReferentiel()
    {
        return $this->formuleResultatReferentiel;
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
     * Get intervenant
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getIntervenant()
    {
        return $this->intervenant;
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
     * Get annee
     *
     * @return \Application\Entity\Db\Annee 
     */
    public function getAnnee()
    {
        return $this->annee;
    }
}
