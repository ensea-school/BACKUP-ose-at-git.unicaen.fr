<?php

namespace Application\Entity\Db;

/**
 * StatutIntervenant
 */
class StatutIntervenant
{
    const ENS_2ND_DEG    = 'ENS_2ND_DEG';
    const ENS_CH         = 'ENS_2ND_DEG';
    const ASS_MI_TPS     = 'ASS_MI_TPS';
    const ATER           = 'ATER';
    const ATER_MI_TPS    = 'ATER_MI_TPS';
    const DOCTOR         = 'DOCTOR';
    const ENS_CDI        = 'ENS_CDI';
    const LECTEUR        = 'LECTEUR';
    const MAITRE_LANG    = 'MAITRE_LANG';
    const BIATSS         = 'BIATSS';
    const SALAR_PRIVE    = 'SALAR_PRIVE';
    const SALAR_PUBLIC   = 'SALAR_PUBLIC';
    const NON_SALAR      = 'NON_SALAR';
    const RETR_UCBN      = 'RETR_UCBN';
    const RETR_HORS_UCBN = 'RETR_HORS_UCBN';
    const ETUD_UCBN      = 'ETUD_UCBN';
    const ETUD_HORS_UCBN = 'ETUD_HORS_UCBN';
    const CHARG_ENS_1AN  = 'CHARG_ENS_1AN';
    const AUTRES         = 'AUTRES';

    static public $vacatairesNonBiatss = array(
        self::SALAR_PRIVE,
        self::SALAR_PUBLIC,
        self::NON_SALAR,
        self::RETR_UCBN,
        self::RETR_HORS_UCBN,
        self::ETUD_UCBN,
        self::ETUD_HORS_UCBN,
        self::CHARG_ENS_1AN,
    );
    
    /**
     * 
     * @return string
     */
    public function __toString()
    {
        return $this->getLibelle();
    }
    
    /**
     * Indique si ce statut correspond aux vacataires non-BIATSS.
     * Déterminant pour la saisie de dossier vacataire.
     * 
     * @return bool
     */
    public function estVacataireNonBiatss()
    {
        return in_array($this->getSourceCode(), self::$vacatairesNonBiatss);
    }
    
    /**
     * Indique si ce statut requiert la saisie d'un dossier vacataire.
     * 
     * @return bool
     */
    public function requiertDossier()
    {
        return in_array($this->getSourceCode(), self::$vacatairesNonBiatss);
    }
    
    /**
     * Indique si ce statut permet la saisie de service prévisionnel ou de référentiel.
     * 
     * @return bool
     */
    public function permetSaisieService()
    {
        return !in_array($this->getSourceCode(), array(
            self::RETR_UCBN,
            self::AUTRES,
        ));
    }
    
    /**
     * @var \Application\Entity\Db\Source
     */
    protected $source;

    /**
     * @var string
     */
    protected $sourceCode;

    /**
     * @var boolean
     */
    protected $depassement;

    /**
     * @var boolean
     */
    protected $fonctionEC;

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
     */
    protected $libelle;

    /**
     * @var float
     */
    protected $serviceStatutaire;

    /**
     * @var float
     */
    protected $plafondReferentiel;

    /**
     * @var float
     */
    protected $maximumHETD;

    /**
     * @var \DateTime
     */
    protected $validiteDebut;

    /**
     * @var \DateTime
     */
    protected $validiteFin;

    /**
     * @var integer
     */
    protected $id;

    /**
     * @var \Application\Entity\Db\TypeIntervenant
     */
    protected $typeIntervenant;

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
     * Set depassement
     *
     * @param boolean $depassement
     * @return StatutIntervenant
     */
    public function setDepassement($depassement)
    {
        $this->depassement = $depassement;

        return $this;
    }

    /**
     * Get depassement
     *
     * @return boolean 
     */
    public function getDepassement()
    {
        return $this->depassement;
    }

    /**
     * Set fonctionEC
     *
     * @param boolean $fonctionEC
     * @return StatutIntervenant
     */
    public function setFonctionEC($fonctionEC)
    {
        $this->fonctionEC = $fonctionEC;

        return $this;
    }

    /**
     * Get fonctionEC
     *
     * @return boolean 
     */
    public function getFonctionEC()
    {
        return $this->fonctionEC;
    }

    /**
     * Set histoCreation
     *
     * @param \DateTime $histoCreation
     * @return StatutIntervenant
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
     * @return StatutIntervenant
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
     * @return StatutIntervenant
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
     * Set libelle
     *
     * @param string $libelle
     * @return StatutIntervenant
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * Get libelle
     *
     * @return string 
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * Set serviceStatutaire
     *
     * @param float $serviceStatutaire
     * @return StatutIntervenant
     */
    public function setServiceStatutaire($serviceStatutaire)
    {
        $this->serviceStatutaire = $serviceStatutaire;

        return $this;
    }

    /**
     * Get serviceStatutaire
     *
     * @return float 
     */
    public function getServiceStatutaire()
    {
        return $this->serviceStatutaire;
    }

    /**
     * Set plafondReferentiel
     *
     * @param float $plafondReferentiel
     * @return StatutIntervenant
     */
    public function setPlafondReferentiel($plafondReferentiel)
    {
        $this->plafondReferentiel = $plafondReferentiel;

        return $this;
    }

    /**
     * Get plafondReferentiel
     *
     * @return float 
     */
    public function getPlafondReferentiel()
    {
        return $this->plafondReferentiel;
    }

    /**
     * Set maximumHETD
     *
     * @param float $maximumHETD
     * @return StatutIntervenant
     */
    public function setMaximumHETD($maximumHETD)
    {
        $this->maximumHETD = $maximumHETD;

        return $this;
    }

    /**
     * Get maximumHETD
     *
     * @return float 
     */
    public function getMaximumHETD()
    {
        return $this->maximumHETD;
    }

    /**
     * Set sourceCode
     *
     * @param string $sourceCode
     * @return StatutIntervenant
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
     * Set validiteDebut
     *
     * @param \DateTime $validiteDebut
     * @return StatutIntervenant
     */
    public function setValiditeDebut($validiteDebut)
    {
        $this->validiteDebut = $validiteDebut;

        return $this;
    }

    /**
     * Get validiteDebut
     *
     * @return \DateTime 
     */
    public function getValiditeDebut()
    {
        return $this->validiteDebut;
    }

    /**
     * Set validiteFin
     *
     * @param \DateTime $validiteFin
     * @return StatutIntervenant
     */
    public function setValiditeFin($validiteFin)
    {
        $this->validiteFin = $validiteFin;

        return $this;
    }

    /**
     * Get validiteFin
     *
     * @return \DateTime 
     */
    public function getValiditeFin()
    {
        return $this->validiteFin;
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
     * Set typeIntervenant
     *
     * @param \Application\Entity\Db\TypeIntervenant $typeIntervenant
     * @return StatutIntervenant
     */
    public function setTypeIntervenant(\Application\Entity\Db\TypeIntervenant $typeIntervenant = null)
    {
        $this->typeIntervenant = $typeIntervenant;

        return $this;
    }

    /**
     * Get typeIntervenant
     *
     * @return \Application\Entity\Db\TypeIntervenant 
     */
    public function getTypeIntervenant()
    {
        return $this->typeIntervenant;
    }

    /**
     * Set histoModificateur
     *
     * @param \Application\Entity\Db\Utilisateur $histoModificateur
     * @return StatutIntervenant
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
     * @return StatutIntervenant
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
     * @return StatutIntervenant
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
     * Set source
     *
     * @param \Application\Entity\Db\Source $source
     * @return StatutIntervenant
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
}
