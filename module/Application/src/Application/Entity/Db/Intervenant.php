<?php

namespace Application\Entity\Db;

use Zend\Form\Annotation;
use Common\Constants;

/**
 * Intervenant
 * 
 * @Annotation\Name("intervenant")
 * @Annotation\Type("Application\Form\Intervenant\AjouterModifier")
 * @Annotation\Hydrator("Application\Entity\Db\Hydrator\Intervenant")
 */
abstract class Intervenant implements IntervenantInterface, HistoriqueAwareInterface
{
    /**
     * @var integer
     */
    protected $id;
    
    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $affectation;
    
    /**
     * @var \DateTime
     * @Annotation\Type("UnicaenApp\Form\Element\DateInfSup")
     * @Annotation\Options({"date_inf_label":"Date de naissance :"})
     */
    private $dateNaissance;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Département de naissance (code INSEE) :"})
     */
    private $depNaissanceCodeInsee;

    /**
     * @var string
     */
    private $depNaissanceLibelle;

    /**
     * @var string
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Validator({"name":"EmailAddress"})
     * @Annotation\Options({"label":"Adresse mail :"})
     */
    private $email;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Nom patronymique :"})
     */
    private $nomPatronymique;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Nom usuel :"})
     */
    private $nomUsuel;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Numéro INSEE :"})
     */
    private $numeroInsee;

    /**
     * @var string
     */
    private $numeroInseeCle;

    /**
     * @var string
     */
    private $numeroInseeProvisoire;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Pays de naissance (code Insee) :"})
     */
    private $paysNaissanceCodeInsee;

    /**
     * @var string
     */
    private $paysNaissanceLibelle;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Pays de nationalité (code Insee) :"})
     */
    private $paysNationaliteCodeInsee;

    /**
     * @var string
     */
    private $paysNationaliteLibelle;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":25}})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Prénom :"})
     */
    private $prenom;

    /**
     * @var string
     */
    private $telMobile;

    /**
     * @var string
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"VIlle de naissance (code Insee) :"})
     */
    private $villeNaissanceCodeInsee;

    /**
     * @var string
     */
    private $villeNaissanceLibelle;

    /**
     * @var \Application\Entity\Db\TypeIntervenant
     */
    private $type;

    /**
     * @var \Application\Entity\Db\Source
     */
    private $source;

    /**
     * @var string
     */
    private $sourceCode;

    /**
     * @var \Application\Entity\Db\Structure
     */
    private $structure;

    /**
     * @var \Application\Entity\Db\Civilite
     * @Annotation\Type("Zend\Form\Element\Select")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Civilité :"})
     */
    private $civilite;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $sectionCnu;
    
    /**
     * @var boolean
     */
    private $primeExcellenceScient;

    /**
     * @var string
     */
    private $telPro;
    
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
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoModificateur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoDestructeur;

    /**
     * @var \Application\Entity\Db\Utilisateur
     */
    protected $histoCreateur;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->annee = new \Doctrine\Common\Collections\ArrayCollection();
        $this->sectionCnu = new \Doctrine\Common\Collections\ArrayCollection();
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
        $f = new \Common\Formatter\NomCompletFormatter(true, $avecCivilite, $avecNomPatro);
        
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
     * Add affectation
     *
     * @param \Application\Entity\Db\Affectation $affectation
     * @return Intervenant
     */
    public function addAffectation(\Application\Entity\Db\Affectation $affectation)
    {
        $this->affectation[] = $affectation;

        return $this;
    }

    /**
     * Remove affectation
     *
     * @param \Application\Entity\Db\Affectation $affectation
     */
    public function removeAffectation(\Application\Entity\Db\Affectation $affectation)
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
     * @param string $numeroInseeProvisoire
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
     * @return string 
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
     * Add sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     * @return Intervenant
     */
    public function addSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu)
    {
        $this->sectionCnu[] = $sectionCnu;

        return $this;
    }

    /**
     * Remove sectionCnu
     *
     * @param \Application\Entity\Db\SectionCnu $sectionCnu
     */
    public function removeSectionCnu(\Application\Entity\Db\SectionCnu $sectionCnu)
    {
        $this->sectionCnu->removeElement($sectionCnu);
    }

    /**
     * Get sectionCnu
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSectionCnu()
    {
        return $this->sectionCnu;
    }

    /**
     * Set source
     *
     * @param Source $source
     * @return Intervenant
     */
    public function setSource(Source $source)
    {
        $this->source = $source;

        return $this;
    }

    /**
     * Get source
     *
     * @return Source 
     */
    public function getSource()
    {
        return $this->source;
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
     * Set historique
     *
     * @param \Application\Entity\Db\Historique $historique
     * @return IntervenantPermanent
     */
    public function setHistorique(\Application\Entity\Db\Historique $historique = null)
    {
        $this->historique = $historique;

        return $this;
    }

    /**
     * Get historique
     *
     * @return \Application\Entity\Db\Historique 
     */
    public function getHistorique()
    {
        return $this->historique;
    }
    
    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * Set id
     *
     * @param \Application\Entity\Db\Intervenant $id
     * @return IntervenantPermanent
     */
    public function setId(\Application\Entity\Db\Intervenant $id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return \Application\Entity\Db\Intervenant 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return IntervenantPermanent
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
     * @return IntervenantPermanent
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
     * Set histoCreateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoCreateur
     * @return IntervenantPermanent
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
    
    
    /*************************** IntervenantInterface ***********************/
    
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

}
