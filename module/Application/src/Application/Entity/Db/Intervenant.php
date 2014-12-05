<?php

namespace Application\Entity\Db;

use Zend\Form\Annotation;
use Common\Constants;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * Intervenant
 * 
 * @Annotation\Name("intervenant")
 * @Annotation\Type("Application\Form\Intervenant\AjouterModifier")
 * @Annotation\Hydrator("Application\Entity\Db\Hydrator\Intervenant")
 */
abstract class Intervenant implements IntervenantInterface, HistoriqueAwareInterface, ValiditeAwareInterface, ResourceInterface
{    
    /**
     * @var \DateTime
     * @Annotation\Type("UnicaenApp\Form\Element\DateInfSup")
     * @Annotation\Options({"date_inf_label":"Date de naissance :"})
     */
    protected $dateNaissance;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Département de naissance (code INSEE) :"})
     */
    protected $depNaissanceCodeInsee;

    /**
     * @var string
     */
    protected $depNaissanceLibelle;

    /**
     * @var string
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Adresse mail :"})
     */
    protected $email;

    /**
     * @var \DateTime
     */
    protected $histoCreation;

    /**
     * @var \DateTime
     */
    protected $histoDestruction;

    /**
     * @var \DateTime
     */
    protected $histoModification;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Nom patronymique :"})
     */
    protected $nomPatronymique;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Nom usuel :"})
     */
    protected $nomUsuel;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Numéro INSEE :"})
     */
    protected $numeroInsee;

    /**
     * @var string
     */
    protected $numeroInseeCle;

    /**
     * @var boolean
     */
    protected $numeroInseeProvisoire;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Pays de naissance (code Insee) :"})
     */
    protected $paysNaissanceCodeInsee;

    /**
     * @var string
     */
    protected $paysNaissanceLibelle;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Pays de nationalité (code Insee) :"})
     */
    protected $paysNationaliteCodeInsee;

    /**
     * @var string
     */
    protected $paysNationaliteLibelle;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Prénom :"})
     */
    protected $prenom;

    /**
     * @var boolean
     */
    protected $primeExcellenceScient;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var string
     */
    protected $telMobile;

    /**
     * @var string
     */
    protected $telPro;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"VIlle de naissance (code Insee) :"})
     */
    protected $villeNaissanceCodeInsee;

    /**
     * @var string
     */
    protected $villeNaissanceLibelle;

    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $affectation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $adresse;

    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var \Application\Entity\Db\StatutIntervenant
     */
    protected $statut;

    /**
     * @var \Application\Entity\Db\Structure
     */
    protected $structure;

    /**
     * @var \Application\Entity\Db\Discipline
     */
    protected $discipline;

    /**
     * @var \Application\Entity\Db\Civilite
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Civilité :"})
     */
    protected $civilite;

    /**
     * @var string
     */
    protected $BIC;

    /**
     * @var string
     */
    protected $IBAN;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;

    /**
     * @var \Application\Entity\Db\TypeIntervenant
     */
    protected $type;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $service;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $serviceDu;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $validation;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $agrement;

    /**
     * @var Utilisateur
     */
    protected $utilisateur;

    /**
     * @var boolean
     */
    protected $premierRecrutement;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    protected $wfIntervenantEtape;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->affectation = new \Doctrine\Common\Collections\ArrayCollection();
        $this->adresse     = new \Doctrine\Common\Collections\ArrayCollection();
        $this->validation  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->agrement    = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set dateNaissance
     *
     * @param \DateTime $dateNaissance
     * @return Intervenant
     */
    public function setDateNaissance($dateNaissance)
    {
        $this->dateNaissance = $dateNaissance;

        return $this;
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissance()
    {
        return $this->dateNaissance;
    }

    /**
     * Set depNaissanceCodeInsee
     *
     * @param string $depNaissanceCodeInsee
     * @return Intervenant
     */
    public function setDepNaissanceCodeInsee($depNaissanceCodeInsee)
    {
        $this->depNaissanceCodeInsee = $depNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get depNaissanceCodeInsee
     *
     * @return string 
     */
    public function getDepNaissanceCodeInsee()
    {
        return $this->depNaissanceCodeInsee;
    }

    /**
     * Set depNaissanceLibelle
     *
     * @param string $depNaissanceLibelle
     * @return Intervenant
     */
    public function setDepNaissanceLibelle($depNaissanceLibelle)
    {
        $this->depNaissanceLibelle = $depNaissanceLibelle;

        return $this;
    }

    /**
     * Get depNaissanceLibelle
     *
     * @return string 
     */
    public function getDepNaissanceLibelle()
    {
        return $this->depNaissanceLibelle;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Intervenant
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return Intervenant
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
     * @return Intervenant
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
     * @return Intervenant
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
     * Set nomPatronymique
     *
     * @param string $nomPatronymique
     * @return Intervenant
     */
    public function setNomPatronymique($nomPatronymique)
    {
        $this->nomPatronymique = $nomPatronymique;

        return $this;
    }

    /**
     * Get nomPatronymique
     *
     * @return string 
     */
    public function getNomPatronymique()
    {
        return $this->nomPatronymique;
    }

    /**
     * Set nomUsuel
     *
     * @param string $nomUsuel
     * @return Intervenant
     */
    public function setNomUsuel($nomUsuel)
    {
        $this->nomUsuel = $nomUsuel;

        return $this;
    }

    /**
     * Get nomUsuel
     *
     * @return string 
     */
    public function getNomUsuel()
    {
        return $this->nomUsuel;
    }

    /**
     * Set numeroInsee
     *
     * @param string $numeroInsee
     * @return Intervenant
     */
    public function setNumeroInsee($numeroInsee)
    {
        $this->numeroInsee = $numeroInsee;

        return $this;
    }

    /**
     * Get numeroInsee
     *
     * @return string 
     */
    public function getNumeroInsee()
    {
        return $this->numeroInsee;
    }

    /**
     * Set numeroInseeCle
     *
     * @param string $numeroInseeCle
     * @return Intervenant
     */
    public function setNumeroInseeCle($numeroInseeCle)
    {
        $this->numeroInseeCle = $numeroInseeCle;

        return $this;
    }

    /**
     * Get numeroInseeCle
     *
     * @return string 
     */
    public function getNumeroInseeCle()
    {
        return $this->numeroInseeCle;
    }

    /**
     * Set numeroInseeProvisoire
     *
     * @param boolean $numeroInseeProvisoire
     * @return Intervenant
     */
    public function setNumeroInseeProvisoire($numeroInseeProvisoire)
    {
        $this->numeroInseeProvisoire = $numeroInseeProvisoire;

        return $this;
    }

    /**
     * Get numeroInseeProvisoire
     *
     * @return boolean 
     */
    public function getNumeroInseeProvisoire()
    {
        return $this->numeroInseeProvisoire;
    }

    /**
     * Set paysNaissanceCodeInsee
     *
     * @param string $paysNaissanceCodeInsee
     * @return Intervenant
     */
    public function setPaysNaissanceCodeInsee($paysNaissanceCodeInsee)
    {
        $this->paysNaissanceCodeInsee = $paysNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get paysNaissanceCodeInsee
     *
     * @return string 
     */
    public function getPaysNaissanceCodeInsee()
    {
        return $this->paysNaissanceCodeInsee;
    }

    /**
     * Set paysNaissanceLibelle
     *
     * @param string $paysNaissanceLibelle
     * @return Intervenant
     */
    public function setPaysNaissanceLibelle($paysNaissanceLibelle)
    {
        $this->paysNaissanceLibelle = $paysNaissanceLibelle;

        return $this;
    }

    /**
     * Get paysNaissanceLibelle
     *
     * @return string 
     */
    public function getPaysNaissanceLibelle()
    {
        return $this->paysNaissanceLibelle;
    }

    /**
     * Set paysNationaliteCodeInsee
     *
     * @param string $paysNationaliteCodeInsee
     * @return Intervenant
     */
    public function setPaysNationaliteCodeInsee($paysNationaliteCodeInsee)
    {
        $this->paysNationaliteCodeInsee = $paysNationaliteCodeInsee;

        return $this;
    }

    /**
     * Get paysNationaliteCodeInsee
     *
     * @return string 
     */
    public function getPaysNationaliteCodeInsee()
    {
        return $this->paysNationaliteCodeInsee;
    }

    /**
     * Set paysNationaliteLibelle
     *
     * @param string $paysNationaliteLibelle
     * @return Intervenant
     */
    public function setPaysNationaliteLibelle($paysNationaliteLibelle)
    {
        $this->paysNationaliteLibelle = $paysNationaliteLibelle;

        return $this;
    }

    /**
     * Get paysNationaliteLibelle
     *
     * @return string 
     */
    public function getPaysNationaliteLibelle()
    {
        return $this->paysNationaliteLibelle;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return Intervenant
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set primeExcellenceScient
     *
     * @param boolean $primeExcellenceScient
     * @return Intervenant
     */
    public function setPrimeExcellenceScient($primeExcellenceScient)
    {
        $this->primeExcellenceScient = $primeExcellenceScient;

        return $this;
    }

    /**
     * Get primeExcellenceScient
     *
     * @return boolean 
     */
    public function getPrimeExcellenceScient()
    {
        return $this->primeExcellenceScient;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return Intervenant
     */
    public function setSourceCode($sourceCode)
    {
        $this->sourceCode = $sourceCode;

        return $this;
    }

    /**
     * Get sourceCode
     *
     * @return string 
     */
    public function getSourceCode()
    {
        return $this->sourceCode;
    }

    /**
     * Set telMobile
     *
     * @param string $telMobile
     * @return Intervenant
     */
    public function setTelMobile($telMobile)
    {
        $this->telMobile = $telMobile;

        return $this;
    }

    /**
     * Get telMobile
     *
     * @return string 
     */
    public function getTelMobile()
    {
        return $this->telMobile;
    }

    /**
     * Set telPro
     *
     * @param string $telPro
     * @return Intervenant
     */
    public function setTelPro($telPro)
    {
        $this->telPro = $telPro;

        return $this;
    }

    /**
     * Get telPro
     *
     * @return string 
     */
    public function getTelPro()
    {
        return $this->telPro;
    }

    /**
     * Set villeNaissanceCodeInsee
     *
     * @param string $villeNaissanceCodeInsee
     * @return Intervenant
     */
    public function setVilleNaissanceCodeInsee($villeNaissanceCodeInsee)
    {
        $this->villeNaissanceCodeInsee = $villeNaissanceCodeInsee;

        return $this;
    }

    /**
     * Get villeNaissanceCodeInsee
     *
     * @return string 
     */
    public function getVilleNaissanceCodeInsee()
    {
        return $this->villeNaissanceCodeInsee;
    }

    /**
     * Set villeNaissanceLibelle
     *
     * @param string $villeNaissanceLibelle
     * @return Intervenant
     */
    public function setVilleNaissanceLibelle($villeNaissanceLibelle)
    {
        $this->villeNaissanceLibelle = $villeNaissanceLibelle;

        return $this;
    }

    /**
     * Get villeNaissanceLibelle
     *
     * @return string 
     */
    public function getVilleNaissanceLibelle()
    {
        return $this->villeNaissanceLibelle;
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
     * Add affectation
     *
     * @param \Application\Entity\Db\AffectationRecherche $affectation
     * @return Intervenant
     */
    public function addAffectation(\Application\Entity\Db\AffectationRecherche $affectation)
    {
        $this->affectation[] = $affectation;

        return $this;
    }

    /**
     * Remove affectation
     *
     * @param \Application\Entity\Db\AffectationRecherche $affectation
     */
    public function removeAffectation(\Application\Entity\Db\AffectationRecherche $affectation)
    {
        $this->affectation->removeElement($affectation);
    }

    /**
     * Get affectation
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAffectation()
    {
        return $this->affectation;
    }

    /**
     * Add adresse
     *
     * @param \Application\Entity\Db\AdresseIntervenant $adresse
     * @return Intervenant
     */
    public function addAdresse(\Application\Entity\Db\AdresseIntervenant $adresse)
    {
        $this->adresse[] = $adresse;

        return $this;
    }

    /**
     * Remove adresse
     *
     * @param \Application\Entity\Db\AdresseIntervenant $adresse
     */
    public function removeAdresse(\Application\Entity\Db\AdresseIntervenant $adresse)
    {
        $this->adresse->removeElement($adresse);
    }

    /**
     * Get adresse
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return Intervenant
     */
    public function setSource(\Application\Entity\Db\Source $source = null)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return \Application\Entity\Db\Source 
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * Set statut
     *
     * @param \Application\Entity\Db\StatutIntervenant $statut
     * @return Intervenant
     */
    public function setStatut(\Application\Entity\Db\StatutIntervenant $statut = null)
    {
        $this->statut = $statut;

        return $this;
    }

    /**
     * Get statut
     *
     * @return \Application\Entity\Db\StatutIntervenant 
     */
    public function getStatut()
    {
        return $this->statut;
    }

    /**
     * Set civilite
     *
     * @param \Application\Entity\Db\Civilite $civilite
     * @return Intervenant
     */
    public function setCivilite(\Application\Entity\Db\Civilite $civilite = null)
    {
        $this->civilite = $civilite;

        return $this;
    }

    /**
     * Get civilite
     *
     * @return \Application\Entity\Db\Civilite 
     */
    public function getCivilite()
    {
        return $this->civilite;
    }

    /**
     * Set BIC
     *
     * @param string $BIC
     * @return Intervenant
     */
    public function setBIC($BIC = null)
    {
        $this->BIC = $BIC;

        return $this;
    }

    /**
     * Get BIC
     *
     * @return string
     */
    public function getBIC()
    {
        return $this->BIC;
    }

    /**
     * Set IBAN
     *
     * @param string $IBAN
     * @return Intervenant
     */
    public function setIBAN($IBAN = null)
    {
        $this->IBAN = $IBAN;

        return $this;
    }

    /**
     * Get IBAN
     *
     * @return string
     */
    public function getIBAN()
    {
        return $this->IBAN;
    }

    /**
     * Set histoDestructeur
     *
     * @param \Application\Entity\Db\Utilisateur $histoDestructeur
     * @return Intervenant
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
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return Intervenant
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
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return Intervenant
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
     * Set type
     *
     * @param \Application\Entity\Db\TypeIntervenant $type
     * @return Intervenant
     */
    public function setType(\Application\Entity\Db\TypeIntervenant $type = null)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return \Application\Entity\Db\TypeIntervenant 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set structure
     *
     * @param \Application\Entity\Db\Structure $structure
     * @return Intervenant
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = null)
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * Get structure
     *
     * @return \Application\Entity\Db\Structure 
     */
    public function getStructure()
    {
        return $this->structure;
    }

    /**
     * Set discipline
     *
     * @param \Application\Entity\Db\Discipline $discipline
     * @return Intervenant
     */
    public function setDiscipline(\Application\Entity\Db\Discipline $discipline = null)
    {
        $this->discipline = $discipline;

        return $this;
    }

    /**
     * Get discipline
     *
     * @return \Application\Entity\Db\Discipline 
     */
    public function getDiscipline()
    {
        return $this->discipline;
    }

    /**
     * Add service
     *
     * @param \Application\Entity\Db\Service $service
     * @return Intervenant
     */
    public function addService(\Application\Entity\Db\Service $service)
    {
        $this->service[] = $service;

        return $this;
    }

    /**
     * Remove service
     *
     * @param \Application\Entity\Db\Service $service
     */
    public function removeService(\Application\Entity\Db\Service $service)
    {
        $this->service->removeElement($service);
    }

    /**
     * Get service
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getService(Annee $annee = null)
    {
        if (null === $annee) {
            return $this->service;
        }
        if (null === $this->service) {
            return null;
        }
        
        $filter   = function(Service $service) use ($annee) { return $annee === $service->getAnnee(); };
        $services = $this->service->filter($filter);
        
        return $services;
    }

    /**
     * Get validation
     * 
     * @param \Application\Entity\Db\TypeValidation $type
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getValidation(TypeValidation $type = null)
    {
        if (null === $type) {
            return $this->validation;
        }
        if (null === $this->validation) {
            return null;
        }
        
        $filter      = function(Validation $validation) use ($type) { return $type === $validation->getTypeValidation(); };
        $validations = $this->validation->filter($filter);
        
        return $validations;
    }

    /**
     * Add serviceDu
     *
     * @param \Application\Entity\Db\ServiceDuIntervenant $serviceDu
     * @return Intervenant
     */
    public function addServiceDu(\Application\Entity\Db\ServiceDuIntervenant $serviceDu)
    {
        $this->serviceDu[] = $serviceDu;

        return $this;
    }

    /**
     * Remove serviceDu
     *
     * @param \Application\Entity\Db\ServiceDuIntervenant $serviceDu
     */
    public function removeServiceDu(\Application\Entity\Db\ServiceDuIntervenant $serviceDu)
    {
        $this->serviceDu->removeElement($serviceDu);
    }

    /**
     * Get serviceDu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getServiceDu()
    {
        return $this->serviceDu;
    }

    /**
     * Add agrement
     *
     * @param \Application\Entity\Db\Agrement $agrement
     * @return Intervenant
     */
    public function addAgrement(\Application\Entity\Db\Agrement $agrement)
    {
        $this->agrement[] = $agrement;

        return $this;
    }

    /**
     * Remove agrement
     *
     * @param \Application\Entity\Db\Agrement $agrement
     */
    public function removeAgrement(\Application\Entity\Db\Agrement $agrement)
    {
        $this->agrement->removeElement($agrement);
    }

    /**
     * Get agrement
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getAgrement(TypeAgrement $typeAgrement = null, Annee $annee = null)
    {
        if (null === $this->agrement) {
            return null;
        }
        if (null === $typeAgrement && null === $annee) {
            return $this->agrement;
        }
        
        $filter   = function(Agrement $agrement) use ($typeAgrement, $annee) {
            if ($typeAgrement && $typeAgrement !== $agrement->getType()) {
                return false;
            }
            if ($annee && $annee !== $agrement->getAnnee()) {
                return false;
            }
            return true; 
        };
        $agrements = $this->agrement->filter($filter);
        
        return $agrements;
    }
    
    /**
     * Get utilisateur
     * 
     * @return Utilisateur
     */
    public function getUtilisateur()
    {
        return $this->utilisateur;
    }
    
    /**
     * Indique si cet intervenant est permanent.
     *
     * @return bool 
     */
    public function estPermanent()
    {
        return $this->getStatut()->estPermanent();
    }
    
    /**
     * Get estUneFemme
     *
     * @return bool 
     */
    public function estUneFemme()
    {
        return Civilite::SEXE_F === $this->getCivilite()->getSexe();
    }
    
    /**
     * Get civilite
     *
     * @return string 
     */
    public function getCiviliteToString()
    {
        return $this->getCivilite()->getLibelleCourt();
    }

    /**
     * Get affectations
     *
     * @return string 
     */
    public function getAffectationsToString()
    {
        return "" . $this->getStructure() ?: "(Inconnue)";
    }

    /**
     * Get source id
     *
     * @return integer 
     * @see \Application\Entity\Db\Source
     */
    public function getSourceToString()
    {
        return $this->getSource()->getLibelle();
    }

    /**
     * Get type id
     *
     * @return integer
     * @see \Application\Entity\Db\TypeIntervenant
     */
    public function getTypeId()
    {
        return $this instanceof IntervenantPermanent ? TypeIntervenant::TYPE_PERMANENT : TypeIntervenant::TYPE_EXTERIEUR;
    }

    /**
     * Retourne la représentation littérale de cet objet.
     * 
     * @return string
     */
    public function __toString()
    {
        return strtoupper($this->getNomUsuel()) . ' ' . ucfirst($this->getPrenom());
    }

    /**
     * Get nomUsuel
     *
     * @return string 
     */
    public function getNomComplet($avecCivilite = false, $avecNomPatro = false)
    {
        $f = new \Common\Filter\NomCompletFormatter(true, $avecCivilite, $avecNomPatro);
        
        return $f->filter($this);
    }

    /**
     * Get dateNaissance
     *
     * @return \DateTime 
     */
    public function getDateNaissanceToString()
    {
        return $this->dateNaissance->format(Constants::DATE_FORMAT);
    }

    /**
     * Get adresse principale
     *
     * @param bool $returnFirstAddressIfNoPrimaryAddressFound
     * @return AdresseIntervenant
     */
    public function getAdressePrincipale($returnFirstAddressIfNoPrimaryAddressFound = false)
    {
        if (!count($this->getAdresse())) {
            return null;
        }
        
        foreach ($this->getAdresse() as $a) { /* @var $a AdresseIntervenant */
            if ($a->getPrincipale()) {
                return $a;
            }
        }
        
        return $returnFirstAddressIfNoPrimaryAddressFound ? reset($this->getAdresse()) : null;
    }

    /**
     * Set premierRecrutement
     *
     * @param null|boolean $premierRecrutement
     * @return Dossier
     */
    public function setPremierRecrutement($premierRecrutement)
    {
        $this->premierRecrutement = $premierRecrutement;

        return $this;
    }

    /**
     * Get premierRecrutement
     *
     * @return null|boolean 
     */
    public function getPremierRecrutement()
    {
        return $this->premierRecrutement;
    }

    /**
     * Add wfIntervenantEtape
     *
     * @param \Application\Entity\Db\WfIntervenantEtape $wfIntervenantEtape
     * @return Intervenant
     */
    public function addWfIntervenantEtape(\Application\Entity\Db\WfIntervenantEtape $wfIntervenantEtape)
    {
        $this->wfIntervenantEtape[] = $wfIntervenantEtape;

        return $this;
    }

    /**
     * Remove wfIntervenantEtape
     *
     * @param \Application\Entity\Db\WfIntervenantEtape $wfIntervenantEtape
     */
    public function removeWfIntervenantEtape(\Application\Entity\Db\WfIntervenantEtape $wfIntervenantEtape)
    {
        $this->wfIntervenantEtape->removeElement($wfIntervenantEtape);
    }

    /**
     * Get wfIntervenantEtape
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getWfIntervenantEtape()
    {
        return $this->wfIntervenantEtape;
    }

    /**
     * Returns the string identifier of the Resource
     *
     * @return string
     * @see ResourceInterface
     */
    public function getResourceId()
    {
        return 'Intervenant';
    }
}
