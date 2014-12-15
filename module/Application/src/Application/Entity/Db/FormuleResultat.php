<?php

namespace Application\Entity\Db;

use Doctrine\ORM\Mapping as ORM;

/**
 * FormuleResultat
 */
class FormuleResultat
{
    /**
     * @var float
     */
    private $aPayer;

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
     * Set aPayer
     *
     * @param float $aPayer
     * @return FormuleResultat
     */
    public function setAPayer($aPayer)
    {
        $this->aPayer = $aPayer;

        return $this;
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
     * Set enseignements
     *
     * @param float $enseignements
     * @return FormuleResultat
     */
    public function setEnseignements($enseignements)
    {
        $this->enseignements = $enseignements;

        return $this;
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
     * Set heuresSolde
     *
     * @param float $heuresSolde
     * @return FormuleResultat
     */
    public function setHeuresSolde($heuresSolde)
    {
        $this->heuresSolde = $heuresSolde;

        return $this;
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
     * Set heuresComplFc
     *
     * @param float $heuresComplFc
     * @return FormuleResultat
     */
    public function setHeuresComplFc($heuresComplFc)
    {
        $this->heuresComplFc = $heuresComplFc;

        return $this;
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
     * Set heuresComplFa
     *
     * @param float $heuresComplFa
     * @return FormuleResultat
     */
    public function setHeuresComplFa($heuresComplFa)
    {
        $this->heuresComplFa = $heuresComplFa;

        return $this;
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
     * Set heuresComplFi
     *
     * @param float $heuresComplFi
     * @return FormuleResultat
     */
    public function setHeuresComplFi($heuresComplFi)
    {
        $this->heuresComplFi = $heuresComplFi;

        return $this;
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
     * Set heuresComplReferentiel
     *
     * @param float $heuresComplReferentiel
     * @return FormuleResultat
     */
    public function setHeuresComplReferentiel($heuresComplReferentiel)
    {
        $this->heuresComplReferentiel = $heuresComplReferentiel;

        return $this;
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
     * Set heuresComplTotal
     *
     * @param float $heuresComplTotal
     * @return FormuleResultat
     */
    public function setHeuresComplTotal($heuresComplTotal)
    {
        $this->heuresComplTotal = $heuresComplTotal;

        return $this;
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
     * Set service
     *
     * @param float $service
     * @return FormuleResultat
     */
    public function setService($service)
    {
        $this->service = $service;

        return $this;
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
     * Set referentiel
     *
     * @param float $referentiel
     * @return FormuleResultat
     */
    public function setReferentiel($referentiel)
    {
        $this->referentiel = $referentiel;

        return $this;
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
     * Set serviceAssure
     *
     * @param float $serviceAssure
     * @return FormuleResultat
     */
    public function setServiceAssure($serviceAssure)
    {
        $this->serviceAssure = $serviceAssure;

        return $this;
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
     * Set serviceDu
     *
     * @param float $serviceDu
     * @return FormuleResultat
     */
    public function setServiceDu($serviceDu)
    {
        $this->serviceDu = $serviceDu;

        return $this;
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
     * Set sousService
     *
     * @param float $sousService
     * @return FormuleResultat
     */
    public function setSousService($sousService)
    {
        $this->sousService = $sousService;

        return $this;
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
     * Set typeVolumeHoraire
     *
     * @param \Application\Entity\Db\TypeVolumeHoraire $typeVolumeHoraire
     * @return FormuleResultat
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
     * Set intervenant
     *
     * @param \Application\Entity\Db\Intervenant $intervenant
     * @return FormuleResultat
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
     * Set etatVolumeHoraire
     *
     * @param \Application\Entity\Db\EtatVolumeHoraire $etatVolumeHoraire
     * @return FormuleResultat
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
     * Set annee
     *
     * @param \Application\Entity\Db\Annee $annee
     * @return FormuleResultat
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
