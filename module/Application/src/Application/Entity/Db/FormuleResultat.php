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
    private $serviceDu;

    /**
     * @var float
     */
    private $enseignements;

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
    private $service;

    /**
     * @var float
     */
    private $heuresSolde;

    /**
     * @var float
     */
    private $heuresComplFi;

    /**
     * @var float
     */
    private $heuresComplFa;

    /**
     * @var float
     */
    private $heuresComplFc;

    /**
     * @var float
     */
    private $heuresComplFcMajorees;

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
    private $formuleResultatServiceReferentiel;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatVolumeHoraire;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $formuleResultatVolumeHoraireReferentiel;

    /**
     * @var \Application\Entity\Db\Intervenant
     */
    private $intervenant;

    /**
     * @var \Application\Entity\Db\Annee
     */
    private $annee;

    /**
     * @var \Application\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Application\Entity\Db\EtatVolumeHoraire
     */
    private $etatVolumeHoraire;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleResultatService = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatServiceReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatVolumeHoraire = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatVolumeHoraireReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set heuresComplFcMajorees
     *
     * @param float $heuresComplFcMajorees
     * @return FormuleResultat
     */
    public function setHeuresComplFcMajorees($heuresComplFcMajorees)
    {
        $this->heuresComplFcMajorees = $heuresComplFcMajorees;

        return $this;
    }

    /**
     * Get heuresComplFcMajorees
     *
     * @return float 
     */
    public function getHeuresComplFcMajorees()
    {
        return $this->heuresComplFcMajorees;
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
     * Set id
     *
     * @param integer $id
     * @return FormuleResultat
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
     * Add formuleResultatService
     *
     * @param \Application\Entity\Db\FormuleResultatService $formuleResultatService
     * @return FormuleResultat
     */
    public function addFormuleResultatService(\Application\Entity\Db\FormuleResultatService $formuleResultatService)
    {
        $this->formuleResultatService[] = $formuleResultatService;

        return $this;
    }

    /**
     * Remove formuleResultatService
     *
     * @param \Application\Entity\Db\FormuleResultatService $formuleResultatService
     */
    public function removeFormuleResultatService(\Application\Entity\Db\FormuleResultatService $formuleResultatService)
    {
        $this->formuleResultatService->removeElement($formuleResultatService);
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
     * Add formuleResultatServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     * @return FormuleResultat
     */
    public function addFormuleResultatServiceReferentiel(\Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel)
    {
        $this->formuleResultatServiceReferentiel[] = $formuleResultatServiceReferentiel;

        return $this;
    }

    /**
     * Remove formuleResultatServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     */
    public function removeFormuleResultatServiceReferentiel(\Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel)
    {
        $this->formuleResultatServiceReferentiel->removeElement($formuleResultatServiceReferentiel);
    }

    /**
     * Get formuleResultatServiceReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleResultatServiceReferentiel()
    {
        return $this->formuleResultatServiceReferentiel;
    }

    /**
     * Add formuleResultatVolumeHoraire
     *
     * @param \Application\Entity\Db\FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire
     * @return FormuleResultat
     */
    public function addFormuleResultatVolumeHoraire(\Application\Entity\Db\FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire)
    {
        $this->formuleResultatVolumeHoraire[] = $formuleResultatVolumeHoraire;

        return $this;
    }

    /**
     * Remove formuleResultatVolumeHoraire
     *
     * @param \Application\Entity\Db\FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire
     */
    public function removeFormuleResultatVolumeHoraire(\Application\Entity\Db\FormuleResultatVolumeHoraire $formuleResultatVolumeHoraire)
    {
        $this->formuleResultatVolumeHoraire->removeElement($formuleResultatVolumeHoraire);
    }

    /**
     * Get formuleResultatVolumeHoraire
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleResultatVolumeHoraire()
    {
        return $this->formuleResultatVolumeHoraire;
    }

    /**
     * Add formuleResultatVolumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel
     * @return FormuleResultat
     */
    public function addFormuleResultatVolumeHoraireReferentiel(\Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel)
    {
        $this->formuleResultatVolumeHoraireReferentiel[] = $formuleResultatVolumeHoraireReferentiel;

        return $this;
    }

    /**
     * Remove formuleResultatVolumeHoraireReferentiel
     *
     * @param \Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel
     */
    public function removeFormuleResultatVolumeHoraireReferentiel(\Application\Entity\Db\FormuleResultatVolumeHoraireReferentiel $formuleResultatVolumeHoraireReferentiel)
    {
        $this->formuleResultatVolumeHoraireReferentiel->removeElement($formuleResultatVolumeHoraireReferentiel);
    }

    /**
     * Get formuleResultatVolumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getFormuleResultatVolumeHoraireReferentiel()
    {
        return $this->formuleResultatVolumeHoraireReferentiel;
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
}
