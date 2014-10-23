<?php

namespace DoctrineORMModule\Proxy\__CG__\Application\Entity\Db;

/**
 * DO NOT EDIT THIS FILE - IT WAS CREATED BY DOCTRINE'S PROXY GENERATOR
 */
class IntervenantExterieur extends \Application\Entity\Db\IntervenantExterieur implements \Doctrine\ORM\Proxy\Proxy
{
    /**
     * @var \Closure the callback responsible for loading properties in the proxy object. This callback is called with
     *      three parameters, being respectively the proxy object to be initialized, the method that triggered the
     *      initialization process and an array of ordered parameters that were passed to that method.
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setInitializer
     */
    public $__initializer__;

    /**
     * @var \Closure the callback responsible of loading properties that need to be copied in the cloned object
     *
     * @see \Doctrine\Common\Persistence\Proxy::__setCloner
     */
    public $__cloner__;

    /**
     * @var boolean flag indicating if this object was already initialized
     *
     * @see \Doctrine\Common\Persistence\Proxy::__isInitialized
     */
    public $__isInitialized__ = false;

    /**
     * @var array properties to be lazy loaded, with keys being the property
     *            names and values being their default values
     *
     * @see \Doctrine\Common\Persistence\Proxy::__getLazyProperties
     */
    public static $lazyPropertiesDefaults = array();



    /**
     * @param \Closure $initializer
     * @param \Closure $cloner
     */
    public function __construct($initializer = null, $cloner = null)
    {

        $this->__initializer__ = $initializer;
        $this->__cloner__      = $cloner;
    }







    /**
     * 
     * @return array
     */
    public function __sleep()
    {
        if ($this->__isInitialized__) {
            return array('__isInitialized__', 'validiteDebut', 'validiteFin', 'typePoste', 'regimeSecu', 'typeIntervenantExterieur', 'situationFamiliale', 'dossier', 'contrat', 'dateNaissance', 'depNaissanceCodeInsee', 'depNaissanceLibelle', 'email', 'histoCreation', 'histoDestruction', 'histoModification', 'nomPatronymique', 'nomUsuel', 'numeroInsee', 'numeroInseeCle', 'numeroInseeProvisoire', 'paysNaissanceCodeInsee', 'paysNaissanceLibelle', 'paysNationaliteCodeInsee', 'paysNationaliteLibelle', 'prenom', 'primeExcellenceScient', 'sourceCode', 'telMobile', 'telPro', 'villeNaissanceCodeInsee', 'villeNaissanceLibelle', 'id', 'affectation', 'adresse', 'source', 'statut', 'structure', 'discipline', 'civilite', 'BIC', 'IBAN', 'histoDestructeur', 'histoModificateur', 'histoCreateur', 'type', 'service', 'validation', 'agrement', 'utilisateur');
        }

        return array('__isInitialized__', 'validiteDebut', 'validiteFin', 'typePoste', 'regimeSecu', 'typeIntervenantExterieur', 'situationFamiliale', 'dossier', 'contrat', 'dateNaissance', 'depNaissanceCodeInsee', 'depNaissanceLibelle', 'email', 'histoCreation', 'histoDestruction', 'histoModification', 'nomPatronymique', 'nomUsuel', 'numeroInsee', 'numeroInseeCle', 'numeroInseeProvisoire', 'paysNaissanceCodeInsee', 'paysNaissanceLibelle', 'paysNationaliteCodeInsee', 'paysNationaliteLibelle', 'prenom', 'primeExcellenceScient', 'sourceCode', 'telMobile', 'telPro', 'villeNaissanceCodeInsee', 'villeNaissanceLibelle', 'id', 'affectation', 'adresse', 'source', 'statut', 'structure', 'discipline', 'civilite', 'BIC', 'IBAN', 'histoDestructeur', 'histoModificateur', 'histoCreateur', 'type', 'service', 'validation', 'agrement', 'utilisateur');
    }

    /**
     * 
     */
    public function __wakeup()
    {
        if ( ! $this->__isInitialized__) {
            $this->__initializer__ = function (IntervenantExterieur $proxy) {
                $proxy->__setInitializer(null);
                $proxy->__setCloner(null);

                $existingProperties = get_object_vars($proxy);

                foreach ($proxy->__getLazyProperties() as $property => $defaultValue) {
                    if ( ! array_key_exists($property, $existingProperties)) {
                        $proxy->$property = $defaultValue;
                    }
                }
            };

        }
    }

    /**
     * 
     */
    public function __clone()
    {
        $this->__cloner__ && $this->__cloner__->__invoke($this, '__clone', array());
    }

    /**
     * Forces initialization of the proxy
     */
    public function __load()
    {
        $this->__initializer__ && $this->__initializer__->__invoke($this, '__load', array());
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __isInitialized()
    {
        return $this->__isInitialized__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitialized($initialized)
    {
        $this->__isInitialized__ = $initialized;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setInitializer(\Closure $initializer = null)
    {
        $this->__initializer__ = $initializer;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __getInitializer()
    {
        return $this->__initializer__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     */
    public function __setCloner(\Closure $cloner = null)
    {
        $this->__cloner__ = $cloner;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific cloning logic
     */
    public function __getCloner()
    {
        return $this->__cloner__;
    }

    /**
     * {@inheritDoc}
     * @internal generated method: use only when explicitly handling proxy specific loading logic
     * @static
     */
    public function __getLazyProperties()
    {
        return self::$lazyPropertiesDefaults;
    }

    
    /**
     * {@inheritDoc}
     */
    public function setValiditeDebut($validiteDebut)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValiditeDebut', array($validiteDebut));

        return parent::setValiditeDebut($validiteDebut);
    }

    /**
     * {@inheritDoc}
     */
    public function getValiditeDebut()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValiditeDebut', array());

        return parent::getValiditeDebut();
    }

    /**
     * {@inheritDoc}
     */
    public function setValiditeFin($validiteFin)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setValiditeFin', array($validiteFin));

        return parent::setValiditeFin($validiteFin);
    }

    /**
     * {@inheritDoc}
     */
    public function getValiditeFin()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValiditeFin', array());

        return parent::getValiditeFin();
    }

    /**
     * {@inheritDoc}
     */
    public function setTypePoste(\Application\Entity\Db\TypePoste $typePoste = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTypePoste', array($typePoste));

        return parent::setTypePoste($typePoste);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypePoste()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypePoste', array());

        return parent::getTypePoste();
    }

    /**
     * {@inheritDoc}
     */
    public function setRegimeSecu(\Application\Entity\Db\RegimeSecu $regimeSecu = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setRegimeSecu', array($regimeSecu));

        return parent::setRegimeSecu($regimeSecu);
    }

    /**
     * {@inheritDoc}
     */
    public function getRegimeSecu()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getRegimeSecu', array());

        return parent::getRegimeSecu();
    }

    /**
     * {@inheritDoc}
     */
    public function setTypeIntervenantExterieur(\Application\Entity\Db\TypeIntervenantExterieur $typeIntervenantExterieur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTypeIntervenantExterieur', array($typeIntervenantExterieur));

        return parent::setTypeIntervenantExterieur($typeIntervenantExterieur);
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeIntervenantExterieur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeIntervenantExterieur', array());

        return parent::getTypeIntervenantExterieur();
    }

    /**
     * {@inheritDoc}
     */
    public function setSituationFamiliale(\Application\Entity\Db\SituationFamiliale $situationFamiliale = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSituationFamiliale', array($situationFamiliale));

        return parent::setSituationFamiliale($situationFamiliale);
    }

    /**
     * {@inheritDoc}
     */
    public function getSituationFamiliale()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSituationFamiliale', array());

        return parent::getSituationFamiliale();
    }

    /**
     * {@inheritDoc}
     */
    public function setDossier(\Application\Entity\Db\Dossier $dossier = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDossier', array($dossier));

        return parent::setDossier($dossier);
    }

    /**
     * {@inheritDoc}
     */
    public function getDossier()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDossier', array());

        return parent::getDossier();
    }

    /**
     * {@inheritDoc}
     */
    public function addContrat(\Application\Entity\Db\Contrat $contrat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addContrat', array($contrat));

        return parent::addContrat($contrat);
    }

    /**
     * {@inheritDoc}
     */
    public function removeContrat(\Application\Entity\Db\Contrat $contrat)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeContrat', array($contrat));

        return parent::removeContrat($contrat);
    }

    /**
     * {@inheritDoc}
     */
    public function getContrat(\Application\Entity\Db\TypeContrat $typeContrat = NULL, \Application\Entity\Db\Structure $structure = NULL, \Application\Entity\Db\Annee $annee = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContrat', array($typeContrat, $structure, $annee));

        return parent::getContrat($typeContrat, $structure, $annee);
    }

    /**
     * {@inheritDoc}
     */
    public function getContratInitial()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getContratInitial', array());

        return parent::getContratInitial();
    }

    /**
     * {@inheritDoc}
     */
    public function getAvenants()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAvenants', array());

        return parent::getAvenants();
    }

    /**
     * {@inheritDoc}
     */
    public function setDateNaissance($dateNaissance)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDateNaissance', array($dateNaissance));

        return parent::setDateNaissance($dateNaissance);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateNaissance()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateNaissance', array());

        return parent::getDateNaissance();
    }

    /**
     * {@inheritDoc}
     */
    public function setDepNaissanceCodeInsee($depNaissanceCodeInsee)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDepNaissanceCodeInsee', array($depNaissanceCodeInsee));

        return parent::setDepNaissanceCodeInsee($depNaissanceCodeInsee);
    }

    /**
     * {@inheritDoc}
     */
    public function getDepNaissanceCodeInsee()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDepNaissanceCodeInsee', array());

        return parent::getDepNaissanceCodeInsee();
    }

    /**
     * {@inheritDoc}
     */
    public function setDepNaissanceLibelle($depNaissanceLibelle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDepNaissanceLibelle', array($depNaissanceLibelle));

        return parent::setDepNaissanceLibelle($depNaissanceLibelle);
    }

    /**
     * {@inheritDoc}
     */
    public function getDepNaissanceLibelle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDepNaissanceLibelle', array());

        return parent::getDepNaissanceLibelle();
    }

    /**
     * {@inheritDoc}
     */
    public function setEmail($email)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setEmail', array($email));

        return parent::setEmail($email);
    }

    /**
     * {@inheritDoc}
     */
    public function getEmail()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getEmail', array());

        return parent::getEmail();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoCreation($histoCreation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoCreation', array($histoCreation));

        return parent::setHistoCreation($histoCreation);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoCreation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoCreation', array());

        return parent::getHistoCreation();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoDestruction($histoDestruction)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoDestruction', array($histoDestruction));

        return parent::setHistoDestruction($histoDestruction);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoDestruction()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoDestruction', array());

        return parent::getHistoDestruction();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoModification($histoModification)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoModification', array($histoModification));

        return parent::setHistoModification($histoModification);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoModification()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoModification', array());

        return parent::getHistoModification();
    }

    /**
     * {@inheritDoc}
     */
    public function setNomPatronymique($nomPatronymique)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNomPatronymique', array($nomPatronymique));

        return parent::setNomPatronymique($nomPatronymique);
    }

    /**
     * {@inheritDoc}
     */
    public function getNomPatronymique()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNomPatronymique', array());

        return parent::getNomPatronymique();
    }

    /**
     * {@inheritDoc}
     */
    public function setNomUsuel($nomUsuel)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNomUsuel', array($nomUsuel));

        return parent::setNomUsuel($nomUsuel);
    }

    /**
     * {@inheritDoc}
     */
    public function getNomUsuel()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNomUsuel', array());

        return parent::getNomUsuel();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumeroInsee($numeroInsee)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumeroInsee', array($numeroInsee));

        return parent::setNumeroInsee($numeroInsee);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumeroInsee()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumeroInsee', array());

        return parent::getNumeroInsee();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumeroInseeCle($numeroInseeCle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumeroInseeCle', array($numeroInseeCle));

        return parent::setNumeroInseeCle($numeroInseeCle);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumeroInseeCle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumeroInseeCle', array());

        return parent::getNumeroInseeCle();
    }

    /**
     * {@inheritDoc}
     */
    public function setNumeroInseeProvisoire($numeroInseeProvisoire)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setNumeroInseeProvisoire', array($numeroInseeProvisoire));

        return parent::setNumeroInseeProvisoire($numeroInseeProvisoire);
    }

    /**
     * {@inheritDoc}
     */
    public function getNumeroInseeProvisoire()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNumeroInseeProvisoire', array());

        return parent::getNumeroInseeProvisoire();
    }

    /**
     * {@inheritDoc}
     */
    public function setPaysNaissanceCodeInsee($paysNaissanceCodeInsee)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPaysNaissanceCodeInsee', array($paysNaissanceCodeInsee));

        return parent::setPaysNaissanceCodeInsee($paysNaissanceCodeInsee);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaysNaissanceCodeInsee()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPaysNaissanceCodeInsee', array());

        return parent::getPaysNaissanceCodeInsee();
    }

    /**
     * {@inheritDoc}
     */
    public function setPaysNaissanceLibelle($paysNaissanceLibelle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPaysNaissanceLibelle', array($paysNaissanceLibelle));

        return parent::setPaysNaissanceLibelle($paysNaissanceLibelle);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaysNaissanceLibelle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPaysNaissanceLibelle', array());

        return parent::getPaysNaissanceLibelle();
    }

    /**
     * {@inheritDoc}
     */
    public function setPaysNationaliteCodeInsee($paysNationaliteCodeInsee)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPaysNationaliteCodeInsee', array($paysNationaliteCodeInsee));

        return parent::setPaysNationaliteCodeInsee($paysNationaliteCodeInsee);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaysNationaliteCodeInsee()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPaysNationaliteCodeInsee', array());

        return parent::getPaysNationaliteCodeInsee();
    }

    /**
     * {@inheritDoc}
     */
    public function setPaysNationaliteLibelle($paysNationaliteLibelle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPaysNationaliteLibelle', array($paysNationaliteLibelle));

        return parent::setPaysNationaliteLibelle($paysNationaliteLibelle);
    }

    /**
     * {@inheritDoc}
     */
    public function getPaysNationaliteLibelle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPaysNationaliteLibelle', array());

        return parent::getPaysNationaliteLibelle();
    }

    /**
     * {@inheritDoc}
     */
    public function setPrenom($prenom)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPrenom', array($prenom));

        return parent::setPrenom($prenom);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrenom()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrenom', array());

        return parent::getPrenom();
    }

    /**
     * {@inheritDoc}
     */
    public function setPrimeExcellenceScient($primeExcellenceScient)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setPrimeExcellenceScient', array($primeExcellenceScient));

        return parent::setPrimeExcellenceScient($primeExcellenceScient);
    }

    /**
     * {@inheritDoc}
     */
    public function getPrimeExcellenceScient()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getPrimeExcellenceScient', array());

        return parent::getPrimeExcellenceScient();
    }

    /**
     * {@inheritDoc}
     */
    public function setSourceCode($sourceCode)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSourceCode', array($sourceCode));

        return parent::setSourceCode($sourceCode);
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceCode()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSourceCode', array());

        return parent::getSourceCode();
    }

    /**
     * {@inheritDoc}
     */
    public function setTelMobile($telMobile)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTelMobile', array($telMobile));

        return parent::setTelMobile($telMobile);
    }

    /**
     * {@inheritDoc}
     */
    public function getTelMobile()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTelMobile', array());

        return parent::getTelMobile();
    }

    /**
     * {@inheritDoc}
     */
    public function setTelPro($telPro)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setTelPro', array($telPro));

        return parent::setTelPro($telPro);
    }

    /**
     * {@inheritDoc}
     */
    public function getTelPro()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTelPro', array());

        return parent::getTelPro();
    }

    /**
     * {@inheritDoc}
     */
    public function setVilleNaissanceCodeInsee($villeNaissanceCodeInsee)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVilleNaissanceCodeInsee', array($villeNaissanceCodeInsee));

        return parent::setVilleNaissanceCodeInsee($villeNaissanceCodeInsee);
    }

    /**
     * {@inheritDoc}
     */
    public function getVilleNaissanceCodeInsee()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVilleNaissanceCodeInsee', array());

        return parent::getVilleNaissanceCodeInsee();
    }

    /**
     * {@inheritDoc}
     */
    public function setVilleNaissanceLibelle($villeNaissanceLibelle)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setVilleNaissanceLibelle', array($villeNaissanceLibelle));

        return parent::setVilleNaissanceLibelle($villeNaissanceLibelle);
    }

    /**
     * {@inheritDoc}
     */
    public function getVilleNaissanceLibelle()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getVilleNaissanceLibelle', array());

        return parent::getVilleNaissanceLibelle();
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        if ($this->__isInitialized__ === false) {
            return (int)  parent::getId();
        }


        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getId', array());

        return parent::getId();
    }

    /**
     * {@inheritDoc}
     */
    public function addAffectation(\Application\Entity\Db\AffectationRecherche $affectation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addAffectation', array($affectation));

        return parent::addAffectation($affectation);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAffectation(\Application\Entity\Db\AffectationRecherche $affectation)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeAffectation', array($affectation));

        return parent::removeAffectation($affectation);
    }

    /**
     * {@inheritDoc}
     */
    public function getAffectation()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAffectation', array());

        return parent::getAffectation();
    }

    /**
     * {@inheritDoc}
     */
    public function addAdresse(\Application\Entity\Db\AdresseIntervenant $adresse)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addAdresse', array($adresse));

        return parent::addAdresse($adresse);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAdresse(\Application\Entity\Db\AdresseIntervenant $adresse)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeAdresse', array($adresse));

        return parent::removeAdresse($adresse);
    }

    /**
     * {@inheritDoc}
     */
    public function getAdresse()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAdresse', array());

        return parent::getAdresse();
    }

    /**
     * {@inheritDoc}
     */
    public function setSource(\Application\Entity\Db\Source $source = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setSource', array($source));

        return parent::setSource($source);
    }

    /**
     * {@inheritDoc}
     */
    public function getSource()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSource', array());

        return parent::getSource();
    }

    /**
     * {@inheritDoc}
     */
    public function setStatut(\Application\Entity\Db\StatutIntervenant $statut = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStatut', array($statut));

        return parent::setStatut($statut);
    }

    /**
     * {@inheritDoc}
     */
    public function getStatut()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStatut', array());

        return parent::getStatut();
    }

    /**
     * {@inheritDoc}
     */
    public function setCivilite(\Application\Entity\Db\Civilite $civilite = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setCivilite', array($civilite));

        return parent::setCivilite($civilite);
    }

    /**
     * {@inheritDoc}
     */
    public function getCivilite()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCivilite', array());

        return parent::getCivilite();
    }

    /**
     * {@inheritDoc}
     */
    public function setBIC($BIC = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setBIC', array($BIC));

        return parent::setBIC($BIC);
    }

    /**
     * {@inheritDoc}
     */
    public function getBIC()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getBIC', array());

        return parent::getBIC();
    }

    /**
     * {@inheritDoc}
     */
    public function setIBAN($IBAN = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setIBAN', array($IBAN));

        return parent::setIBAN($IBAN);
    }

    /**
     * {@inheritDoc}
     */
    public function getIBAN()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getIBAN', array());

        return parent::getIBAN();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoDestructeur(\Application\Entity\Db\Utilisateur $histoDestructeur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoDestructeur', array($histoDestructeur));

        return parent::setHistoDestructeur($histoDestructeur);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoDestructeur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoDestructeur', array());

        return parent::getHistoDestructeur();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoModificateur(\Application\Entity\Db\Utilisateur $histoModificateur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoModificateur', array($histoModificateur));

        return parent::setHistoModificateur($histoModificateur);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoModificateur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoModificateur', array());

        return parent::getHistoModificateur();
    }

    /**
     * {@inheritDoc}
     */
    public function setHistoCreateur(\Application\Entity\Db\Utilisateur $histoCreateur = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setHistoCreateur', array($histoCreateur));

        return parent::setHistoCreateur($histoCreateur);
    }

    /**
     * {@inheritDoc}
     */
    public function getHistoCreateur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getHistoCreateur', array());

        return parent::getHistoCreateur();
    }

    /**
     * {@inheritDoc}
     */
    public function setType(\Application\Entity\Db\TypeIntervenant $type = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setType', array($type));

        return parent::setType($type);
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getType', array());

        return parent::getType();
    }

    /**
     * {@inheritDoc}
     */
    public function setStructure(\Application\Entity\Db\Structure $structure = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setStructure', array($structure));

        return parent::setStructure($structure);
    }

    /**
     * {@inheritDoc}
     */
    public function getStructure()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getStructure', array());

        return parent::getStructure();
    }

    /**
     * {@inheritDoc}
     */
    public function setDiscipline(\Application\Entity\Db\Discipline $discipline = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'setDiscipline', array($discipline));

        return parent::setDiscipline($discipline);
    }

    /**
     * {@inheritDoc}
     */
    public function getDiscipline()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDiscipline', array());

        return parent::getDiscipline();
    }

    /**
     * {@inheritDoc}
     */
    public function addService(\Application\Entity\Db\Service $service)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addService', array($service));

        return parent::addService($service);
    }

    /**
     * {@inheritDoc}
     */
    public function removeService(\Application\Entity\Db\Service $service)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeService', array($service));

        return parent::removeService($service);
    }

    /**
     * {@inheritDoc}
     */
    public function getService(\Application\Entity\Db\Annee $annee = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getService', array($annee));

        return parent::getService($annee);
    }

    /**
     * {@inheritDoc}
     */
    public function getValidation(\Application\Entity\Db\TypeValidation $type = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getValidation', array($type));

        return parent::getValidation($type);
    }

    /**
     * {@inheritDoc}
     */
    public function addAgrement(\Application\Entity\Db\Agrement $agrement)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'addAgrement', array($agrement));

        return parent::addAgrement($agrement);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAgrement(\Application\Entity\Db\Agrement $agrement)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'removeAgrement', array($agrement));

        return parent::removeAgrement($agrement);
    }

    /**
     * {@inheritDoc}
     */
    public function getAgrement(\Application\Entity\Db\TypeAgrement $typeAgrement = NULL, \Application\Entity\Db\Annee $annee = NULL)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAgrement', array($typeAgrement, $annee));

        return parent::getAgrement($typeAgrement, $annee);
    }

    /**
     * {@inheritDoc}
     */
    public function getUtilisateur()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getUtilisateur', array());

        return parent::getUtilisateur();
    }

    /**
     * {@inheritDoc}
     */
    public function estPermanent()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estPermanent', array());

        return parent::estPermanent();
    }

    /**
     * {@inheritDoc}
     */
    public function estUneFemme()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'estUneFemme', array());

        return parent::estUneFemme();
    }

    /**
     * {@inheritDoc}
     */
    public function getCiviliteToString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getCiviliteToString', array());

        return parent::getCiviliteToString();
    }

    /**
     * {@inheritDoc}
     */
    public function getAffectationsToString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAffectationsToString', array());

        return parent::getAffectationsToString();
    }

    /**
     * {@inheritDoc}
     */
    public function getSourceToString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getSourceToString', array());

        return parent::getSourceToString();
    }

    /**
     * {@inheritDoc}
     */
    public function getTypeId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getTypeId', array());

        return parent::getTypeId();
    }

    /**
     * {@inheritDoc}
     */
    public function __toString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, '__toString', array());

        return parent::__toString();
    }

    /**
     * {@inheritDoc}
     */
    public function getNomComplet($avecCivilite = false, $avecNomPatro = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getNomComplet', array($avecCivilite, $avecNomPatro));

        return parent::getNomComplet($avecCivilite, $avecNomPatro);
    }

    /**
     * {@inheritDoc}
     */
    public function getDateNaissanceToString()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getDateNaissanceToString', array());

        return parent::getDateNaissanceToString();
    }

    /**
     * {@inheritDoc}
     */
    public function getAdressePrincipale($returnFirstAddressIfNoPrimaryAddressFound = false)
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getAdressePrincipale', array($returnFirstAddressIfNoPrimaryAddressFound));

        return parent::getAdressePrincipale($returnFirstAddressIfNoPrimaryAddressFound);
    }

    /**
     * {@inheritDoc}
     */
    public function getResourceId()
    {

        $this->__initializer__ && $this->__initializer__->__invoke($this, 'getResourceId', array());

        return parent::getResourceId();
    }

}
