<?php

namespace Formule\Entity\Db;

use Application\Entity\Db\Intervenant;
use Service\Entity\Db\EtatVolumeHoraire;
use Service\Entity\Db\TypeVolumeHoraire;

class FormuleResultat
{
    use FormuleResultatTypesHeuresTrait;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var float
     */
    private $serviceDu;

    /**
     * @var float
     */
    private $solde;

    /**
     * @var float
     */
    private $sousService;

    /**
     * @var float
     */
    private $heuresCompl;

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
     * @var \Service\Entity\Db\TypeVolumeHoraire
     */
    private $typeVolumeHoraire;

    /**
     * @var \Service\Entity\Db\EtatVolumeHoraire
     */
    private $etatVolumeHoraire;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->formuleResultatService                  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatServiceReferentiel       = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatVolumeHoraire            = new \Doctrine\Common\Collections\ArrayCollection();
        $this->formuleResultatVolumeHoraireReferentiel = new \Doctrine\Common\Collections\ArrayCollection();
    }



    public function init(Intervenant $intervenant, TypeVolumeHoraire $typeVolumeHoraire, EtatVolumeHoraire $etatVolumeHoraire)
    {
        $this->intervenant       = $intervenant;
        $this->typeVolumeHoraire = $typeVolumeHoraire;
        $this->etatVolumeHoraire = $etatVolumeHoraire;
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
     * Get serviceDu
     *
     * @return float
     */
    public function getServiceDu()
    {
        return $this->serviceDu;
    }



    /**
     * Get solde
     *
     * @return float
     */
    public function getSolde()
    {
        return $this->solde;
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
     * Get heuresCompl
     *
     * @return float
     */
    public function getHeuresCompl()
    {
        return $this->heuresCompl;
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
     * Get formuleResultatServiceReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatServiceReferentiel()
    {
        return $this->formuleResultatServiceReferentiel;
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
     * Get formuleResultatVolumeHoraireReferentiel
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getFormuleResultatVolumeHoraireReferentiel()
    {
        return $this->formuleResultatVolumeHoraireReferentiel;
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
     * Get typeVolumeHoraire
     *
     * @return \Service\Entity\Db\TypeVolumeHoraire
     */
    public function getTypeVolumeHoraire()
    {
        return $this->typeVolumeHoraire;
    }



    /**
     * Get etatVolumeHoraire
     *
     * @return \Service\Entity\Db\EtatVolumeHoraire
     */
    public function getEtatVolumeHoraire()
    {
        return $this->etatVolumeHoraire;
    }
}
