<?php

namespace Application\Entity\Db;

use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * MiseEnPaiement
 */
class MiseEnPaiement implements HistoriqueAwareInterface, ResourceInterface
{
    const A_VALIDER             = 'a-valider';
    const A_METTRE_EN_PAIEMENT  = 'a-mettre-en-paiement';
    const MIS_EN_PAIEMENT       = 'mis-en-paiement';

    /**
     * @var \DateTime
     */
    private $dateMiseEnPaiement;

    /**
     * @var \DateTime
     */
    private $dateValidation;

    /**
     * @var \DateTime
     */
    private $histoCreation;

    /**
     * @var \DateTime
     */
    private $histoDestruction;

    /**
     * @var \DateTime
     */
    private $histoModification;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Application\Entity\Db\Periode
     */
    private $periodePaiement;

    /**
     * @var float
     */
    private $heures;

    /**
     * @var TypeHeures
     */
    private $typeHeures;

    /**
     * @var \Application\Entity\Db\CentreCout
     */
    private $centreCout;

    /**
     * @var \Application\Entity\Db\Validation
     */
    private $validation;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoDestructeur;

    /**
     * @var \Application\Entity\Db\FormuleResultatService
     */
    private $formuleResultatService;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    private $histoCreateur;

    /**
     * @var \Application\Entity\Db\FormuleResultatServiceReferentiel
     */
    private $formuleResultatServiceReferentiel;

    /**
     * miseEnPaiementIntervenantStructure
     *
     * @var MiseEnPaiementIntervenantStructure
     */
    protected $miseEnPaiementIntervenantStructure;


    public function __construct()
    {
        $this->miseEnPaiementIntervenantStructure   = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dateMiseEnPaiement
     *
     * @param \DateTime $dateMiseEnPaiement
     * @return MiseEnPaiement
     */
    public function setDateMiseEnPaiement($dateMiseEnPaiement)
    {
        $this->dateMiseEnPaiement = $dateMiseEnPaiement;

        return $this;
    }

    /**
     * Get dateMiseEnPaiement
     *
     * @return \DateTime 
     */
    public function getDateMiseEnPaiement()
    {
        return $this->dateMiseEnPaiement;
    }

    /**
     * Set dateValidation
     *
     * @param \DateTime $dateValidation
     * @return MiseEnPaiement
     */
    public function setDateValidation($dateValidation)
    {
        $this->dateValidation = $dateValidation;

        return $this;
    }

    /**
     * Get dateValidation
     *
     * @return \DateTime 
     */
    public function getDateValidation()
    {
        return $this->dateValidation;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return MiseEnPaiement
     */
    public function setHistoCreation($histoCreation)
    {
        $this->histoCreation = $histoCreation;

        return $this;
    }

    /**
     * Get histoCreation
     *
     * @return \DateTime 
     */
    public function getHistoCreation()
    {
        return $this->histoCreation;
    }

    /**
     * Set histoDestruction
     *
     * @param \DateTime $histoDestruction
     * @return MiseEnPaiement
     */
    public function setHistoDestruction($histoDestruction)
    {
        $this->histoDestruction = $histoDestruction;

        return $this;
    }

    /**
     * Get histoDestruction
     *
     * @return \DateTime 
     */
    public function getHistoDestruction()
    {
        return $this->histoDestruction;
    }

    /**
     * Set histoModification
     *
     * @param \DateTime $histoModification
     * @return MiseEnPaiement
     */
    public function setHistoModification($histoModification)
    {
        $this->histoModification = $histoModification;

        return $this;
    }

    /**
     * Get histoModification
     *
     * @return \DateTime 
     */
    public function getHistoModification()
    {
        return $this->histoModification;
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
     * Set periodePaiement
     *
     * @param \Application\Entity\Db\Periode $periodePaiement
     * @return MiseEnPaiement
     */
    public function setPeriodePaiement(\Application\Entity\Db\Periode $periodePaiement = null)
    {
        $this->periodePaiement = $periodePaiement;

        return $this;
    }

    /**
     * Get periodePaiement
     *
     * @return \Application\Entity\Db\Periode 
     */
    public function getPeriodePaiement()
    {
        return $this->periodePaiement;
    }

    /**
     * Set heures
     *
     * @param float $heures
     * @return MiseEnPaiement
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
     * Set typeHeures
     *
     * @param \Application\Entity\Db\TypeHeures $typeHeures
     * @return self
     */
    public function setTypeHeures(\Application\Entity\Db\TypeHeures $typeHeures = null)
    {
        $this->typeHeures = $typeHeures;

        return $this;
    }

    /**
     * Get typeHeures
     *
     * @return \Application\Entity\Db\TypeHeures
     */
    public function getTypeHeures()
    {
        return $this->typeHeures;
    }

    /**
     * Set centreCout
     *
     * @param \Application\Entity\Db\CentreCout $centreCout
     * @return MiseEnPaiement
     */
    public function setCentreCout(\Application\Entity\Db\CentreCout $centreCout = null)
    {
        $this->centreCout = $centreCout;

        return $this;
    }

    /**
     * Get centreCout
     *
     * @return \Application\Entity\Db\CentreCout
     */
    public function getCentreCout()
    {
        return $this->centreCout;
    }

    /**
     * Set validation
     *
     * @param \Application\Entity\Db\Validation $validation
     * @return MiseEnPaiement
     */
    public function setValidation(\Application\Entity\Db\Validation $validation = null)
    {
        $this->validation = $validation;

        return $this;
    }

    /**
     * Get validation
     *
     * @return \Application\Entity\Db\Validation 
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return MiseEnPaiement
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = null)
    {
        $this->histoModificateur = $histoModificateur;

        return $this;
    }

    /**
     * Get histoModificateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoModificateur()
    {
        return $this->histoModificateur;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return MiseEnPaiement
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = null)
    {
        $this->histoDestructeur = $histoDestructeur;

        return $this;
    }

    /**
     * Get histoDestructeur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoDestructeur()
    {
        return $this->histoDestructeur;
    }

    /**
     * Set formuleResultatService
     *
     * @param \Application\Entity\Db\FormuleResultatService $formuleResultatService
     * @return MiseEnPaiement
     */
    public function setFormuleResultatService(\Application\Entity\Db\FormuleResultatService $formuleResultatService = null)
    {
        $this->formuleResultatService = $formuleResultatService;

        return $this;
    }

    /**
     * Get formuleResultatService
     *
     * @return \Application\Entity\Db\FormuleResultatService 
     */
    public function getFormuleResultatService()
    {
        return $this->formuleResultatService;
    }

    /**
     *
     * @return ServiceAPayerInterface
     */
    public function getServiceAPayer()
    {
        if ($this->formuleResultatService           ) return $this->formuleResultatService;
        if ($this->formuleResultatServiceReferentiel) return $this->formuleResultatServiceReferentiel;
        return null;
    }

    /**
     *
     * @param ServiceAPayerInterface $serviceAPayer
     * @return self
     */
    public function setServiceAPayer(ServiceAPayerInterface $serviceAPayer = null )
    {
        if ($serviceAPayer instanceof FormuleResultatService           ){
            $this->setFormuleResultatService( $serviceAPayer );
            $this->setFormuleResultatServiceReferentiel();
        }elseif ($serviceAPayer instanceof FormuleResultatServiceReferentiel){
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel( $serviceAPayer );
        }else{
            $this->setFormuleResultatService();
            $this->setFormuleResultatServiceReferentiel();
        }
        return $this;
    }

    /**
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return MiseEnPaiement
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = null)
    {
        $this->histoCreateur = $histoCreateur;

        return $this;
    }

    /**
     * Get histoCreateur
     *
     * @return \Application\Entity\Db\Utilisateur 
     */
    public function getHistoCreateur()
    {
        return $this->histoCreateur;
    }

    /**
     * Set formuleResultatServiceReferentiel
     *
     * @param \Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel
     * @return MiseEnPaiement
     */
    public function setFormuleResultatServiceReferentiel(\Application\Entity\Db\FormuleResultatServiceReferentiel $formuleResultatServiceReferentiel = null)
    {
        $this->formuleResultatServiceReferentiel = $formuleResultatServiceReferentiel;

        return $this;
    }

    /**
     * Get formuleResultatServiceReferentiel
     *
     * @return \Application\Entity\Db\FormuleResultatServiceReferentiel
     */
    public function getFormuleResultatServiceReferentiel()
    {
        return $this->formuleResultatServiceReferentiel;
    }

    /**
     * Get miseEnPaiementIntervenantStructure
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMiseEnPaiementIntervenantStructure()
    {
        return $this->miseEnPaiementIntervenantStructure;
    }

    /**
     *
     * @return string
     */
    public function getEtat()
    {
        if (! $this->getValidation()) return self::A_VALIDER;
        if (! $this->getDateMiseEnPaiement()) return self::A_METTRE_EN_PAIEMENT;
        return self::MIS_EN_PAIEMENT;
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'MiseEnPaiement';
    }
}
